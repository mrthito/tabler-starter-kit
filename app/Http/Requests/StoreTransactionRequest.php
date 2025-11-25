<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_category_id' => ['required', 'exists:transaction_categories,id'],
            'reference_number' => ['nullable', 'string', 'max:100', 'unique:transactions,reference_number'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'type' => ['required', 'string', 'in:income,expense'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'transaction_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:transaction_date'],
            'status' => ['nullable', 'string', 'in:pending,completed,cancelled,failed'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tax_type' => ['nullable', 'string', 'max:50'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'transaction_category_id' => 'category',
            'reference_number' => 'reference number',
            'title' => 'title',
            'amount' => 'amount',
            'currency' => 'currency',
            'type' => 'type',
            'payment_method' => 'payment method',
            'transaction_date' => 'transaction date',
            'due_date' => 'due date',
            'tax_amount' => 'tax amount',
            'tax_rate' => 'tax rate',
        ];
    }
}
