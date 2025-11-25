<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionService
{
    public function __construct(protected Transaction $transaction) {}

    public function paginate($perPage = 20, $filters = [])
    {
        $query = $this->transaction->with(['category', 'creator', 'updater']);

        // Apply filters
        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('transaction_category_id', $filters['category_id']);
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->where('transaction_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->where('transaction_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('transaction_date', 'desc')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate reference number if not provided
        if (empty($data['reference_number'])) {
            $data['reference_number'] = Transaction::generateReferenceNumber();
        }

        // Set created_by if not provided
        if (!isset($data['created_by']) && auth('admin')->check()) {
            $data['created_by'] = auth('admin')->id();
        }

        return $this->transaction->create($data);
    }

    public function update(Transaction $transaction, array $data)
    {
        // Set updated_by if not provided
        if (!isset($data['updated_by']) && auth('admin')->check()) {
            $data['updated_by'] = auth('admin')->id();
        }

        return $transaction->update($data);
    }

    public function delete($transaction, $rows = [])
    {
        if ($transaction == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->transaction->whereIn('id', $rows)->delete();
            return;
        }
        if ($transaction instanceof Transaction) {
            return $transaction->delete();
        }
        return $this->transaction->where('id', $transaction)->delete();
    }

    /**
     * Get financial summary
     */
    public function getSummary($filters = [])
    {
        $baseQuery = $this->transaction->where('status', 'completed');

        // Apply filters
        if (isset($filters['date_from']) && $filters['date_from']) {
            $baseQuery->where('transaction_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $baseQuery->where('transaction_date', '<=', $filters['date_to']);
        }

        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount') ?? 0;
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount') ?? 0;

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_amount' => $totalIncome - $totalExpense,
        ];
    }
}
