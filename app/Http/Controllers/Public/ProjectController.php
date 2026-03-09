<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function index(Request $request): View
    {
        $categorySlug = $request->string('category')->toString() ?: null;

        $projects = $this->projectRepository->allPublished($categorySlug);

        // Kategori untuk filter tab — satu query, tidak diulang
        $categories = Category::forProjects()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('public.projects.index', compact('projects', 'categories', 'categorySlug'));
    }

    public function show(string $slug): View
    {
        $project = $this->projectRepository->findBySlug($slug);

        return view('public.projects.show', compact('project'));
    }
}
