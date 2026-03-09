<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private readonly Post $model
    ) {}

    /**
     * Artikel published dengan pagination.
     * Filter opsional berdasarkan tag slug.
     */
    public function paginatePublished(?string $tagSlug = null, int $perPage = 9): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'media', 'tags'])  // eager load — no N+1
            ->published()
            ->latest()
            ->when($tagSlug, fn ($q) =>
                $q->withAnyTags([$tagSlug])
            )
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Artikel terbaru untuk section home — load minimal (no content).
     */
    public function getRecent(int $limit = 3): Collection
    {
        return $this->model
            ->select(['id', 'category_id', 'title', 'slug', 'excerpt', 'published_at', 'views'])
            ->with(['category', 'media'])          // tidak load tags untuk performa
            ->published()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Satu artikel published via slug.
     * Abort 404 otomatis jika tidak ditemukan atau masih draft.
     */
    public function findPublishedBySlug(string $slug): Post
    {
        return $this->model
            ->with(['category', 'media', 'tags'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Artikel terkait — ambil berdasarkan tag yang sama, exclude artikel saat ini.
     */
    public function getRelated(Post $post, int $limit = 2): Collection
    {
        $tagNames = $post->tags->pluck('name')->toArray();

        // Jika tidak ada tag, fallback ke artikel terbaru dari kategori yang sama
        if (empty($tagNames)) {
            return $this->model
                ->select(['id', 'category_id', 'title', 'slug', 'excerpt', 'published_at'])
                ->with(['media'])
                ->published()
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->latest()
                ->limit($limit)
                ->get();
        }

        return $this->model
            ->select(['id', 'category_id', 'title', 'slug', 'excerpt', 'published_at'])
            ->with(['media'])
            ->published()
            ->withAnyTags($tagNames)
            ->where('id', '!=', $post->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Tabel admin — search by title + filter by status.
     */
    public function paginateForAdmin(string $search = '', string $status = '', int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'media'])
            ->when($search, fn ($q) =>
                $q->where('title', 'like', "%{$search}%")
            )
            ->when($status, fn ($q) =>
                $q->where('status', $status)
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Satu artikel via id — admin, include semua status.
     */
    public function findById(int $id): Post
    {
        return $this->model
            ->with(['category', 'media', 'tags'])
            ->findOrFail($id);
    }

    public function create(array $data): Post
    {
        return $this->model->create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->fill($data)->save();

        return $post->refresh();
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
