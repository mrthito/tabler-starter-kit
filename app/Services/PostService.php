<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostService
{
    /**
     * Create a new PostService instance.
     */
    public function __construct(protected Post $post)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->post->with('postCategories')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle categories
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        // Create post
        $post = $this->post->create($data);

        // Attach categories
        if (!empty($categories)) {
            $post->postCategories()->sync($categories);
        }

        return $post;
    }

    public function update(Post $post, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle categories
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        // Update post
        $post->update($data);

        // Sync categories
        if (isset($categories)) {
            $post->postCategories()->sync($categories);
        }

        return $post;
    }

    public function delete($post, $rows = [])
    {
        if ($post == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->post->whereIn('id', $rows)->delete();
            return;
        }
        if ($post instanceof Post) {
            return $post->delete();
        }
        return $this->post->where('id', $post)->delete();
    }

    public function getCategories()
    {
        return PostCategory::where('status', true)->orderBy('name')->get();
    }
}
