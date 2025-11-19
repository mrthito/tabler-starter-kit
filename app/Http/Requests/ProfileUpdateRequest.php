<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->mode === 'avatar') {
            return [
                'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'],
            ];
        }
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(Admin::class)->ignore($this->user()->id),
            ],
        ];
    }
}
