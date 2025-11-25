<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(protected PageService $pageService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = $this->pageService->paginate($request->per_page ?? 20);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $this->pageService->create($request->validated());

        return redirect()->route('admin.pages.index')->with('success', __('Page created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $this->pageService->update($page, $request->validated());

        return redirect()->route('admin.pages.index')->with('success', __('Page updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $page)
    {
        $this->pageService->delete($page, $request->rows);

        return redirect()->route('admin.pages.index')->with('success', __('Page deleted successfully'));
    }
}
