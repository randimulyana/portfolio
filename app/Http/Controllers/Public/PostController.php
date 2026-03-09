<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function index(Request $request): View
    {
        $tagSlug = $request->string('tag')->toString() ?: null;

        $posts = $this->postRepository->paginatePublished($tagSlug, perPage: 9);

        return view('public.posts.index', compact('posts', 'tagSlug'));
    }

    public function show(string $slug): View
    {
        $post = $this->postRepository->findPublishedBySlug($slug);

        // Tambah view count — tidak trigger updated_at (incrementQuietly di model)
        $post->incrementViews();

        // Related articles — sudah di-load di repository, no N+1
        $related = $this->postRepository->getRelated($post, limit: 2);

        return view('public.posts.show', compact('post', 'related'));
    }
}
