<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private const STORAGE_LIMIT_BYTES = 15 * 1024 * 1024 * 1024;

    public function index()
    {
        return view('admin.media.index');
    }

    public function folders()
    {
        $folders = Media::where('type', 'folder')
            ->orderBy('name')
            ->get()
            ->map(fn(Media $item) => [
                'id' => $item->id,
                'name' => $item->name,
                'parent_id' => $item->parent_id,
                'path' => $this->getFolderPath($item),
            ]);

        return response()->json(['data' => $folders]);
    }

    private function getFolderPath(Media $folder, $path = ''): string
    {
        $path = $folder->name . ($path ? '/' . $path : '');

        if ($folder->parent_id) {
            $parent = Media::find($folder->parent_id);
            if ($parent && $parent->type === 'folder') {
                return $this->getFolderPath($parent, $path);
            }
        }

        return $path;
    }

    public function list(Request $request)
    {
        $query = Media::query();

        $parentId = $request->input('parent_id');
        if ($parentId === null || $parentId === 'null' || $parentId === '') {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', $parentId);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $media = $query->get();

        // Separate folders and files
        $folders = $media->filter(function ($item) {
            return $item instanceof Media && $item->exists && $item->type === 'folder';
        })->sortBy('name');

        $files = $media->filter(function ($item) {
            return $item instanceof Media && $item->exists && $item->type !== 'folder';
        })->sortByDesc('created_at');

        // Combine: folders first, then files
        $sortedMedia = $folders->concat($files);

        $payload = $sortedMedia->map(fn(Media $item) => $this->mapToResponse($item))->values();
        $usedBytes = $media->where('type', '!=', 'folder')->sum(fn(Media $item) => $item->getAttributes()['size'] ?? 0);

        return response()->json([
            'data' => $payload,
            'meta' => [
                'total' => $payload->count(),
                'used_bytes' => $usedBytes,
                'storage_limit_bytes' => self::STORAGE_LIMIT_BYTES,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'files' => 'sometimes|required',
            'files.*' => 'file|max:10240',
            'parent_id' => 'nullable|exists:media,id',
        ]);

        if ($request->has('name') && !$request->hasFile('files')) {
            return $this->createFolder($request);
        }

        $uploadedFiles = Arr::wrap($request->file('files'));
        $disk = $this->defaultDisk();
        $created = [];
        $parentId = $this->normalizeParentId($request->input('parent_id'));

        foreach ($uploadedFiles as $file) {
            if (!$file) {
                continue;
            }

            $path = $file->store('media', 'public');
            $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension() ?: 'file');
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);

            $thumbnailPath = null;
            $thumbnailUrl = null;
            if ($isImage) {
                $thumbnailPath = $this->generateThumbnail($disk->path($path), $extension);
                if ($thumbnailPath) {
                    $thumbnailUrl = $disk->url($thumbnailPath);
                }
            }

            $media = Media::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $extension,
                'size' => $file->getSize(),
                'url' => $disk->url($path),
                'thumbnail' => $thumbnailUrl,
                'parent_id' => $parentId,
            ]);

            $created[] = $media;
        }

        return response()->json([
            'data' => $created,
        ], 201);
    }

    private function createFolder(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media,id',
        ]);

        $parentId = $this->normalizeParentId($data['parent_id'] ?? null);

        if ($parentId) {
            $parent = Media::find($parentId);
            if (!$parent || $parent->type !== 'folder') {
                return response()->json(['error' => 'Parent must be a folder'], 400);
            }
        }

        $folder = Media::create([
            'name' => $data['name'],
            'type' => 'folder',
            'path' => '',
            'size' => 0,
            'url' => null,
            'parent_id' => $parentId,
        ]);

        if (!$folder || !$folder->exists) {
            return response()->json(['error' => 'Failed to create folder'], 500);
        }

        return response()->json([
            'data' => $this->mapToResponse($folder),
        ], 201);
    }

    public function update(Request $request, $media)
    {
        $media = Media::findOrFail($media);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'parent_id' => 'sometimes|nullable|exists:media,id',
            'action' => 'sometimes|in:move,copy',
        ]);

        if ($request->has('action') && isset($data['action'])) {
            $parentId = $this->normalizeParentId($data['parent_id'] ?? null);
            if ($data['action'] === 'move') {
                return $this->moveFile($media, $parentId);
            } elseif ($data['action'] === 'copy') {
                return $this->copyFile($media, $parentId);
            }
        }

        // Rename operation
        if ($request->has('name')) {
            $media->update(['name' => $data['name']]);
        }

        // Update parent if provided
        if ($request->has('parent_id')) {
            $parentId = $this->normalizeParentId($data['parent_id'] ?? null);
            $media->update(['parent_id' => $parentId]);
        }

        $updated = $media->fresh();
        if (!$updated || !$updated->exists) {
            return response()->json(['error' => 'Media not found after update'], 404);
        }

        return response()->json([
            'data' => $this->mapToResponse($updated),
        ]);
    }

    private function moveFile($media, ?int $parentId)
    {
        if (!$media instanceof Media) {
            $media = Media::findOrFail($media);
        }

        $parentId = $this->normalizeParentId($parentId);

        if ($media->isFolder() && $parentId) {
            // Prevent moving folder into itself
            if ($parentId === $media->id) {
                return response()->json(['error' => 'Cannot move folder into itself'], 400);
            }

            // Check if trying to move into a descendant folder
            $parent = Media::find($parentId);
            if ($parent && $parent->isFolder()) {
                $allDescendants = $this->getAllDescendantIds($media);
                if (in_array($parentId, $allDescendants)) {
                    return response()->json(['error' => 'Cannot move folder into its own subfolder'], 400);
                }
            }
        }

        $media->update(['parent_id' => $parentId]);

        $updated = $media->fresh();
        if (!$updated || !$updated->exists) {
            return response()->json(['error' => 'Media not found after move'], 404);
        }

        return response()->json([
            'data' => $this->mapToResponse($updated),
        ]);
    }

    private function getAllDescendantIds($folder): array
    {
        if (!$folder instanceof Media) {
            $folder = Media::findOrFail($folder);
        }

        if (!$folder->isFolder()) {
            return [];
        }

        $ids = [];
        $children = $folder->children()->get();

        foreach ($children as $child) {
            if (!$child || !$child->exists) {
                continue;
            }
            $ids[] = $child->id;
            if ($child->isFolder()) {
                $ids = array_merge($ids, $this->getAllDescendantIds($child));
            }
        }

        return $ids;
    }

    private function copyFile($media, ?int $parentId)
    {
        if (!$media instanceof Media) {
            $media = Media::findOrFail($media);
        }

        $parentId = $this->normalizeParentId($parentId);

        if ($media->isFolder()) {
            return response()->json(['error' => 'Copying folders is not supported'], 400);
        }

        $disk = $this->defaultDisk();
        $originalPath = $media->path;

        if (!$originalPath || !$disk->exists($originalPath)) {
            return response()->json(['error' => 'Source file not found'], 404);
        }

        $pathInfo = pathinfo($originalPath);
        $ext = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
        $newName = $pathInfo['filename'] . '_copy' . $ext;
        $newPath = dirname($originalPath) . '/' . $newName;

        // Ensure unique filename
        $counter = 1;
        while ($disk->exists($newPath)) {
            $newName = $pathInfo['filename'] . '_copy_' . $counter . $ext;
            $newPath = dirname($originalPath) . '/' . $newName;
            $counter++;
        }

        $disk->copy($originalPath, $newPath);

        $copied = Media::create([
            'name' => $media->name,
            'path' => $newPath,
            'type' => $media->type,
            'size' => $media->size,
            'url' => $disk->url($newPath),
            'thumbnail' => null,
            'parent_id' => $parentId,
        ]);

        if (!$copied || !$copied->exists) {
            return response()->json(['error' => 'Failed to copy file'], 500);
        }

        // Copy thumbnail if it exists
        if ($media->thumbnail) {
            $thumbnailUrl = $media->thumbnail;
            $baseUrl = $disk->url('');
            if (str_starts_with($thumbnailUrl, $baseUrl)) {
                $thumbPath = str_replace($baseUrl, '', $thumbnailUrl);
                $thumbPath = ltrim($thumbPath, '/');
                if ($disk->exists($thumbPath)) {
                    $thumbInfo = pathinfo($thumbPath);
                    $ext = isset($thumbInfo['extension']) ? '.' . $thumbInfo['extension'] : '.jpg';
                    $newThumbName = 'thumb_' . $pathInfo['filename'] . '_copy' . $ext;
                    $newThumbPath = dirname($thumbPath) . '/' . $newThumbName;

                    // Ensure unique thumbnail name
                    $counter = 1;
                    while ($disk->exists($newThumbPath)) {
                        $newThumbName = 'thumb_' . $pathInfo['filename'] . '_copy_' . $counter . $ext;
                        $newThumbPath = dirname($thumbPath) . '/' . $newThumbName;
                        $counter++;
                    }

                    $disk->copy($thumbPath, $newThumbPath);
                    $copied->update(['thumbnail' => $disk->url($newThumbPath)]);
                }
            }
        }

        return response()->json([
            'data' => $this->mapToResponse($copied),
        ], 201);
    }

    public function destroy($media)
    {
        $media = Media::find($media);
        if (!$media || !$media->exists) {
            return response()->json(['error' => 'Media not found'], 404);
        }

        if ($media->isFolder()) {
            // Check if folder has children
            if ($media->children()->count() > 0) {
                return response()->json(['error' => 'Cannot delete folder with contents. Please delete all items inside first.'], 400);
            }
            // Folders don't have files to delete, just delete from DB
            $media->delete();
            return response()->json(['message' => 'Folder deleted successfully']);
        }

        $disk = $this->defaultDisk();

        // Delete main file
        if ($media->path && $disk->exists($media->path)) {
            $disk->delete($media->path);
        }

        // Delete thumbnail if exists
        if ($media->thumbnail) {
            $thumbnailUrl = $media->thumbnail;
            $baseUrl = $disk->url('');
            if (str_starts_with($thumbnailUrl, $baseUrl)) {
                $thumbnailPath = str_replace($baseUrl, '', $thumbnailUrl);
                $thumbnailPath = ltrim($thumbnailPath, '/');
                if ($disk->exists($thumbnailPath)) {
                    $disk->delete($thumbnailPath);
                }
            }
        }

        $media->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    private function mapToResponse(Media $item): array
    {
        if (!$item || !$item->exists) {
            throw new \InvalidArgumentException('Media item is null or does not exist');
        }

        $sizeBytes = $item->getAttributes()['size'] ?? 0;
        $isFolder = $item->type === 'folder';

        $url = null;
        if (!$isFolder && $item->path) {
            $url = $item->url ?? $this->defaultDisk()->url($item->path);
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'type' => $isFolder ? 'folder' : $this->normalizeType($item->type),
            'size' => $isFolder ? '-' : $this->formatBytes($sizeBytes),
            'date' => $item->updated_at?->format('M d, Y') ?? $item->created_at->format('M d, Y'),
            'url' => $url,
            'thumbnail' => $item->thumbnail ?? null,
            'parent_id' => $item->parent_id,
            'location' => 'My Drive',
            'size_bytes' => $sizeBytes,
        ];
    }

    private function normalizeType(?string $extension): string
    {
        $extension = strtolower((string) $extension);
        return match ($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp' => 'image',
            'pdf' => 'pdf',
            'txt' => 'txt',
            'xls', 'xlsx' => 'excel',
            'ppt', 'pptx' => 'ppt',
            default => 'file',
        };
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return "0 B";
        }

        $units = ["B", "KB", "MB", "GB", "TB"];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);
        return number_format($value, 1) . ' ' . $units[$power];
    }

    private function defaultDisk(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    private function generateThumbnail(string $imagePath, string $extension, int $maxWidth = 300, int $maxHeight = 300): ?string
    {
        if (!function_exists('imagecreatefromjpeg')) {
            return null;
        }

        try {
            $sourceImage = match (strtolower($extension)) {
                'jpg', 'jpeg' => @imagecreatefromjpeg($imagePath),
                'png' => @imagecreatefrompng($imagePath),
                'gif' => @imagecreatefromgif($imagePath),
                'webp' => @imagecreatefromwebp($imagePath),
                'bmp' => @imagecreatefrombmp($imagePath),
                default => null,
            };

            if (!$sourceImage) {
                return null;
            }

            $sourceWidth = imagesx($sourceImage);
            $sourceHeight = imagesy($sourceImage);

            if ($sourceWidth <= $maxWidth && $sourceHeight <= $maxHeight) {
                imagedestroy($sourceImage);
                return null;
            }

            $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
            $thumbWidth = (int) ($sourceWidth * $ratio);
            $thumbHeight = (int) ($sourceHeight * $ratio);

            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);

            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);

            if (in_array(strtolower($extension), ['png', 'gif', 'webp'])) {
                $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                imagefill($thumbnail, 0, 0, $transparent);
            }

            imagecopyresampled(
                $thumbnail,
                $sourceImage,
                0,
                0,
                0,
                0,
                $thumbWidth,
                $thumbHeight,
                $sourceWidth,
                $sourceHeight
            );

            $dir = dirname($imagePath);
            $filename = pathinfo($imagePath, PATHINFO_FILENAME);
            $thumbPath = $dir . '/thumb_' . $filename . '.jpg';

            imagejpeg($thumbnail, $thumbPath, 85);
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);

            $relativePath = str_replace($this->defaultDisk()->path(''), '', $thumbPath);
            return ltrim(str_replace('\\', '/', $relativePath), '/');
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    private function normalizeParentId($parentId): ?int
    {
        if (empty($parentId) || $parentId === '' || $parentId === 'null' || $parentId === '0') {
            return null;
        }

        return (int) $parentId;
    }
}
