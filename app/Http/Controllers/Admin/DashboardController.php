<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Ambil statistik dalam satu query masing-masing — tidak ada N+1
        $stats = [
            'total_projects'    => Project::count(),
            'published_projects'=> Project::published()->count(),
            'total_posts'       => Post::count(),
            'published_posts'   => Post::published()->count(),
        ];

        // Aktivitas terbaru — 5 item, eager load minimal
        $recentProjects = Project::select(['id', 'slug', 'title', 'status', 'created_at'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPosts = Post::select(['id', 'slug', 'title', 'status', 'published_at', 'created_at'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProjects', 'recentPosts'));
    }
}
