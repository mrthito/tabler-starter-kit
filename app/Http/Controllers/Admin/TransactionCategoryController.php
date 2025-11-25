<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionCategoryRequest;
use App\Http\Requests\UpdateTransactionCategoryRequest;
use App\Models\TransactionCategory;
use App\Services\TransactionCategoryService;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function __construct(protected TransactionCategoryService $transactionCategoryService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactionCategories = $this->transactionCategoryService->paginate($request->per_page ?? 20);

        return view('admin.transaction-categories.index', compact('transactionCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.transaction-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionCategoryRequest $request)
    {
        $this->transactionCategoryService->create($request->validated());

        return redirect()->route('admin.transaction-categories.index')->with('success', __('Transaction Category created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionCategory $transactionCategory)
    {
        return view('admin.transaction-categories.edit', compact('transactionCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionCategoryRequest $request, TransactionCategory $transactionCategory)
    {
        $this->transactionCategoryService->update($transactionCategory, $request->validated());

        return redirect()->route('admin.transaction-categories.index')->with('success', __('Transaction Category updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $transactionCategory)
    {
        $this->transactionCategoryService->delete($transactionCategory, $request->rows);

        return redirect()->route('admin.transaction-categories.index')->with('success', __('Transaction Category deleted successfully'));
    }
}
