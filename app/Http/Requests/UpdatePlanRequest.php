<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('plans', 'slug')->ignore($this->plan)],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['nullable', 'numeric', 'min:0'],
            'yearly_price' => ['nullable', 'numeric', 'min:0'],
            'yearly_discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'currency' => ['nullable', 'string', 'max:3'],
            'has_trial' => ['nullable', 'boolean'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'detail' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'plan_type' => ['nullable', 'string', 'in:free,paid,enterprise,custom'],
            'sort_order' => ['nullable', 'integer'],
            'is_popular' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'slug' => 'slug',
            'monthly_price' => 'monthly price',
            'yearly_price' => 'yearly price',
            'yearly_discount_percentage' => 'yearly discount percentage',
            'currency' => 'currency',
            'trial_days' => 'trial days',
            'plan_type' => 'plan type',
        ];
    }
}
