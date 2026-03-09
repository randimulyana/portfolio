<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    /**
     * Daftar artikel published untuk halaman Blog publik (dengan pagination).
     */
    public function paginatePublished(?string $tagSlug = null, int $perPage = 9): LengthAwarePaginator;

    /**
     * Artikel terbaru untuk section Blog di halaman Home.
     */
    public function getRecent(int $limit = 3): Collection;

    /**
     * Ambil satu artikel via slug — hanya yang published.
     */
    public function findPublishedBySlug(string $slug): Post;

    /**
     * Artikel terkait berdasarkan tags yang sama.
     */
    public function getRelated(Post $post, int $limit = 2): Collection;

    /**
     * Daftar semua artikel untuk tabel admin (dengan pagination + search + filter status).
     */
    public function paginateForAdmin(string $search = '', string $status = '', int $perPage = 15): LengthAwarePaginator;

    /**
     * Ambil satu artikel via id (admin) — include draft.
     */
    public function findById(int $id): Post;

    /**
     * Buat artikel baru.
     */
    public function create(array $data): Post;

    /**
     * Update artikel.
     */
    public function update(Post $post, array $data): Post;

    /**
     * Soft-delete artikel.
     */
    public function delete(Post $post): void;
}
