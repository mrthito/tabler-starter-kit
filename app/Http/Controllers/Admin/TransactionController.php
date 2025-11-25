<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionCategoryService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService,
        protected TransactionCategoryService $transactionCategoryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'type' => $request->type,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        $transactions = $this->transactionService->paginate($request->per_page ?? 20, $filters);
        $summary = $this->transactionService->getSummary($filters);
        $categories = $this->transactionCategoryService->getAll();

        return view('admin.transactions.index', compact('transactions', 'summary', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->transactionCategoryService->getAll();
        return view('admin.transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $this->transactionService->create($request->validated());

        return redirect()->route('admin.transactions.index')->with('success', __('Transaction created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $categories = $this->transactionCategoryService->getAll();
        return view('admin.transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->transactionService->update($transaction, $request->validated());

        return redirect()->route('admin.transactions.index')->with('success', __('Transaction updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $transaction)
    {
        $this->transactionService->delete($transaction, $request->rows);

        return redirect()->route('admin.transactions.index')->with('success', __('Transaction deleted successfully'));
    }
}
