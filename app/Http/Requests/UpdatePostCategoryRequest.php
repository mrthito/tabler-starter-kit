<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostCategoryRequest extends FormRequest
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
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('post_categories', 'slug')->ignore($this->postCategory)],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'exists:post_categories,id', Rule::notIn([$this->postCategory->id])],
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
            'description' => 'description',
            'status' => 'status',
            'parent_id' => 'parent category',
        ];
    }
}
