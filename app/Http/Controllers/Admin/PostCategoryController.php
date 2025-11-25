<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use App\Models\PostCategory;
use App\Services\PostCategoryService;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function __construct(protected PostCategoryService $postCategoryService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $postCategories = $this->postCategoryService->paginate($request->per_page ?? 20);

        return view('admin.post-categories.index', compact('postCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = $this->postCategoryService->getParentCategories();

        return view('admin.post-categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostCategoryRequest $request)
    {
        $this->postCategoryService->create($request->validated());

        return redirect()->route('admin.post-categories.index')->with('success', __('Post category created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCategory $postCategory)
    {
        $parentCategories = $this->postCategoryService->getParentCategories($postCategory->id);

        return view('admin.post-categories.edit', compact('postCategory', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostCategoryRequest $request, PostCategory $postCategory)
    {
        $this->postCategoryService->update($postCategory, $request->validated());

        return redirect()->route('admin.post-categories.index')->with('success', __('Post category updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $postCategory)
    {
        $this->postCategoryService->delete($postCategory, $request->rows);

        return redirect()->route('admin.post-categories.index')->with('success', __('Post category deleted successfully'));
    }
}
