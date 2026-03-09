<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    /**
     * Semua proyek published, eager load relasi yang dibutuhkan.
     * Dipakai di halaman publik.
     */
    public function allPublished(?string $categorySlug = null): Collection;

    /**
     * Featured projects untuk halaman Home.
     */
    public function getFeatured(int $limit = 3): Collection;

    /**
     * Daftar semua proyek untuk tabel admin (dengan pagination + search).
     */
    public function paginateForAdmin(string $search = '', int $perPage = 15): LengthAwarePaginator;

    /**
     * Ambil satu proyek via slug (publik) — 404 jika tidak ditemukan.
     */
    public function findBySlug(string $slug): Project;

    /**
     * Ambil satu proyek via id (admin).
     */
    public function findById(int $id): Project;

    /**
     * Buat proyek baru.
     */
    public function create(array $data): Project;

    /**
     * Update proyek yang sudah ada.
     */
    public function update(Project $project, array $data): Project;

    /**
     * Soft-delete proyek.
     */
    public function delete(Project $project): void;
}
