<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostService $postService
    ) {}

    public function index(Request $request): View
    {
        $posts = $this->postRepository->paginateForAdmin(
            search : $request->string('search')->trim()->toString(),
            status : $request->string('status')->toString(),
            perPage: 15
        );

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::forPosts()->orderBy('name')->get(['id', 'name']);

        return view('admin.posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->postService->create($request->validated());

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::forPosts()->orderBy('name')->get(['id', 'name']);
        $post->loadMissing(['category', 'media', 'tags']);

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->postService->update($post, $request->validated());

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->postService->delete($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
