<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private readonly Project $model
    ) {}

    /**
     * Semua proyek published, di-sort berdasarkan sort_order.
     * Eager load category untuk menghindari N+1.
     */
    public function allPublished(?string $categorySlug = null): Collection
    {
        return $this->model
            ->with(['category', 'media'])         // eager load — no N+1
            ->published()
            ->ordered()
            ->when($categorySlug, fn ($q) =>
                $q->whereHas('category', fn ($q) =>
                    $q->where('slug', $categorySlug)
                )
            )
            ->get();
    }

    /**
     * Featured projects — dipakai di halaman Home.
     */
    public function getFeatured(int $limit = 3): Collection
    {
        return $this->model
            ->with(['media'])                     // hanya media yang dibutuhkan
            ->published()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Tabel admin — search by title, eager load category.
     * withCount tidak dipakai karena tidak ada relasi yang perlu dihitung.
     */
    public function paginateForAdmin(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'media'])
            ->when($search, fn ($q) =>
                $q->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();                  // query string (search) ikut di link pagination
    }

    /**
     * Satu proyek via slug — hanya published.
     * Abort 404 otomatis jika tidak ditemukan.
     */
    public function findBySlug(string $slug): Project
    {
        return $this->model
            ->with(['category', 'media', 'tags'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Satu proyek via id — admin, include semua status.
     */
    public function findById(int $id): Project
    {
        return $this->model
            ->with(['category', 'media', 'tags'])
            ->findOrFail($id);
    }

    /**
     * Buat proyek — data sudah divalidasi oleh Form Request.
     */
    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    /**
     * Update proyek — gunakan fill + save agar model events tetap berjalan.
     */
    public function update(Project $project, array $data): Project
    {
        $project->fill($data)->save();

        return $project->refresh();
    }

    /**
     * Soft-delete — data masih ada di DB, bisa di-restore.
     */
    public function delete(Project $project): void
    {
        $project->delete();
    }
}
