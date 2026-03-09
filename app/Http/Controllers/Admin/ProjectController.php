<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ProjectService $projectService
    ) {}

    /**
     * Daftar proyek — search query diteruskan ke repository.
     * Livewire akan menggantikan ini nanti, tapi controller tetap ada sebagai fallback.
     */
    public function index(Request $request): View
    {
        $projects = $this->projectRepository->paginateForAdmin(
            search : $request->string('search')->trim()->toString(),
            perPage: 15
        );

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $categories = Category::forProjects()->orderBy('name')->get(['id', 'name']);

        return view('admin.projects.create', compact('categories'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        // $request->validated() — hanya data yang lolos validasi
        $this->projectService->create($request->validated());

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil disimpan.');
    }

    public function edit(Project $project): View
    {
        $categories = Category::forProjects()->orderBy('name')->get(['id', 'name']);

        // Load relasi yang dibutuhkan form
        $project->loadMissing(['category', 'media', 'tags']);

        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projectService->update($project, $request->validated());

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->delete($project);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}
