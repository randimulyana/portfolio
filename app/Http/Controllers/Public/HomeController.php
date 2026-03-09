<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function index(): View
    {
        // Semua query sudah eager load di repository — no N+1
        $featuredProjects = $this->projectRepository->getFeatured(limit: 3);
        $recentPosts      = $this->postRepository->getRecent(limit: 3);

        return view('public.home', compact('featuredProjects', 'recentPosts'));
    }
}
