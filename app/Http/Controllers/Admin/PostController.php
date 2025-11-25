<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $this->postService->paginate($request->per_page ?? 20);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->postService->getCategories();

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $this->postService->create($request->validated());

        return redirect()->route('admin.posts.index')->with('success', __('Post created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->load('postCategories');
        $categories = $this->postService->getCategories();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($post, $request->validated());

        return redirect()->route('admin.posts.index')->with('success', __('Post updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $post)
    {
        $this->postService->delete($post, $request->rows);

        return redirect()->route('admin.posts.index')->with('success', __('Post deleted successfully'));
    }
}
