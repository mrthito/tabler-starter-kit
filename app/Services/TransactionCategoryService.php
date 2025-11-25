<?php

namespace App\Services;

use App\Models\TransactionCategory;
use Illuminate\Support\Str;

class TransactionCategoryService
{
    public function __construct(protected TransactionCategory $transactionCategory) {}

    public function paginate($perPage = 20)
    {
        return $this->transactionCategory->orderBy('sort_order')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $this->transactionCategory->create($data);
    }

    public function update(TransactionCategory $transactionCategory, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $transactionCategory->update($data);
    }

    public function delete($transactionCategory, $rows = [])
    {
        if ($transactionCategory == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->transactionCategory->whereIn('id', $rows)->delete();
            return;
        }
        if ($transactionCategory instanceof TransactionCategory) {
            return $transactionCategory->delete();
        }
        return $this->transactionCategory->where('id', $transactionCategory)->delete();
    }

    public function getAll()
    {
        return $this->transactionCategory->where('status', true)->orderBy('sort_order')->get();
    }
}
