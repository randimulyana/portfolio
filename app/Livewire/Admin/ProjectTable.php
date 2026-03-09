<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Services\ProjectService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectTable extends Component
{
    use WithPagination;

    // #[Url] → search query ikut di URL agar bisa di-bookmark / share
    #[Url(as: 'q', history: true)]
    public string $search = '';

    public string $sortBy    = 'created_at';
    public string $sortDir   = 'desc';
    public int    $perPage   = 15;

    // Reset ke halaman 1 saat search berubah
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    /**
     * Toggle published ↔ draft langsung dari tabel.
     * Inject service via parameter (method injection) agar tidak terbebani constructor.
     */
    public function toggleStatus(int $id, ProjectService $projectService): void
    {
        $project = Project::findOrFail($id);
        $projectService->toggleStatus($project);

        $this->dispatch('notify', message: 'Status proyek diperbarui.');
    }

    public function toggleFeatured(int $id, ProjectService $projectService): void
    {
        $project = Project::findOrFail($id);
        $projectService->toggleFeatured($project);
    }

    /**
     * Delete dengan konfirmasi — konfirmasi dialog ada di Alpine.js di view.
     */
    public function delete(int $id, ProjectService $projectService): void
    {
        $project = Project::findOrFail($id);
        $projectService->delete($project);

        $this->dispatch('notify', message: 'Proyek dihapus.');
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['category', 'media'])           // no N+1
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.project-table', [
            'projects' => $projects,
        ]);
    }
}
