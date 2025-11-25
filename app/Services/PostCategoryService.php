<?php

namespace App\Services;

use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategoryService
{
    /**
     * Create a new PostCategoryService instance.
     */
    public function __construct(protected PostCategory $postCategory)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->postCategory->with('parent')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle parent_id - set to null if empty
        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        return $this->postCategory->create($data);
    }

    public function update(PostCategory $postCategory, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle parent_id - set to null if empty
        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        // Prevent setting itself as parent
        if ($data['parent_id'] == $postCategory->id) {
            $data['parent_id'] = null;
        }

        return $postCategory->update($data);
    }

    public function delete($postCategory, $rows = [])
    {
        if ($postCategory == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->postCategory->whereIn('id', $rows)->delete();
            return;
        }
        if ($postCategory instanceof PostCategory) {
            return $postCategory->delete();
        }
        return $this->postCategory->where('id', $postCategory)->delete();
    }

    public function getParentCategories($excludeId = null)
    {
        $query = $this->postCategory->where('status', true)->whereNull('parent_id');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->orderBy('name')->get();
    }

    public function getAllCategories($excludeId = null)
    {
        $query = $this->postCategory->where('status', true);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->orderBy('name')->get();
    }
}
