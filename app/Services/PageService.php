<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Str;

class PageService
{
    /**
     * Create a new PageService instance.
     */
    public function __construct(protected Page $page)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->page->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $this->page->create($data);
    }

    public function update(Page $page, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $page->update($data);
    }

    public function delete($page, $rows = [])
    {
        if ($page == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->page->whereIn('id', $rows)->delete();
            return;
        }
        if ($page instanceof Page) {
            return $page->delete();
        }
        return $this->page->where('id', $page)->delete();
    }
}
