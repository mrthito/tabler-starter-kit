<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Support\Str;

class PlanService
{
    /**
     * Create a new PlanService instance.
     */
    public function __construct(protected Plan $plan)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->plan->orderBy('sort_order')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle features - ensure it's an array and filter empty values
        if (isset($data['features'])) {
            if (is_array($data['features'])) {
                $data['features'] = array_values(array_filter(array_map('trim', $data['features'])));
            } elseif (is_string($data['features'])) {
                $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
            }
        }

        return $this->plan->create($data);
    }

    public function update(Plan $plan, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle features - ensure it's an array and filter empty values
        if (isset($data['features'])) {
            if (is_array($data['features'])) {
                $data['features'] = array_values(array_filter(array_map('trim', $data['features'])));
            } elseif (is_string($data['features'])) {
                $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
            }
        }

        return $plan->update($data);
    }

    public function delete($plan, $rows = [])
    {
        if ($plan == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->plan->whereIn('id', $rows)->delete();
            return;
        }
        if ($plan instanceof Plan) {
            return $plan->delete();
        }
        return $this->plan->where('id', $plan)->delete();
    }
}
