<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Services\PostService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostTable extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'status', history: true)]
    public string $filterStatus = '';

    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';
    public int    $perPage = 15;

    public function updatedSearch(): void      { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function sort(string $column): void
    {
        $this->sortBy  = ($this->sortBy === $column) ? $this->sortBy  : $column;
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function delete(int $id, PostService $postService): void
    {
        $post = Post::findOrFail($id);
        $postService->delete($post);
        $this->dispatch('notify', message: 'Artikel dihapus.');
    }

    public function publish(int $id, PostService $postService): void
    {
        $post = Post::findOrFail($id);
        $postService->publish($post);
    }

    public function unpublish(int $id, PostService $postService): void
    {
        $post = Post::findOrFail($id);
        $postService->unpublish($post);
    }

    public function render()
    {
        $statuses = PostStatus::cases();

        $posts = Post::query()
            ->with(['category'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn ($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.post-table', compact('posts', 'statuses'));
    }
}