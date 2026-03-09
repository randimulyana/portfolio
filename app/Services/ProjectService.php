<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Buat proyek baru beserta thumbnail dan tags.
     * Dibungkus transaction agar konsisten — jika media upload gagal,
     * project tidak jadi tersimpan.
     */
    public function create(array $validatedData): Project
    {
        return DB::transaction(function () use ($validatedData) {
            $tags = $this->extractTags($validatedData);
            $thumbnail = $this->extractThumbnail($validatedData);

            $project = $this->projectRepository->create($validatedData);

            if ($thumbnail) {
                $project->addMedia($thumbnail)
                         ->toMediaCollection('thumbnail');
            }

            if (!empty($tags)) {
                $project->syncTags($tags);
            }

            return $project;
        });
    }

    /**
     * Update proyek — thumbnail diganti jika ada file baru.
     */
    public function update(Project $project, array $validatedData): Project
    {
        return DB::transaction(function () use ($project, $validatedData) {
            $tags = $this->extractTags($validatedData);
            $thumbnail = $this->extractThumbnail($validatedData);

            $project = $this->projectRepository->update($project, $validatedData);

            if ($thumbnail) {
                // clearMediaCollection otomatis hapus file lama sebelum upload baru
                $project->clearMediaCollection('thumbnail');
                $project->addMedia($thumbnail)
                         ->toMediaCollection('thumbnail');
            }

            if ($tags !== null) {
                $project->syncTags($tags);
            }

            return $project;
        });
    }

    /**
     * Hapus proyek (soft delete) beserta media-nya.
     */
    public function delete(Project $project): void
    {
        DB::transaction(function () use ($project) {
            // Hapus media dari storage sebelum soft-delete record
            $project->clearMediaCollection('thumbnail');
            $project->clearMediaCollection('screenshots');

            $this->projectRepository->delete($project);
        });
    }

    /**
     * Toggle status published ↔ draft.
     * Dipanggil dari Livewire tabel admin dengan satu klik.
     */
    public function toggleStatus(Project $project): Project
    {
        $newStatus = $project->status === ProjectStatus::Published
            ? ProjectStatus::Draft
            : ProjectStatus::Published;

        return $this->projectRepository->update($project, ['status' => $newStatus]);
    }

    /**
     * Toggle featured on/off.
     */
    public function toggleFeatured(Project $project): Project
    {
        return $this->projectRepository->update($project, [
            'is_featured' => ! $project->is_featured,
        ]);
    }

    // ─── Private helpers ──────────────────────────────────────────────

    /**
     * Pisahkan tags dari array data agar tidak masuk ke fillable model.
     * Return null jika key 'tags' tidak ada (beda dengan array kosong).
     */
    private function extractTags(array &$data): ?array
    {
        if (! array_key_exists('tags', $data)) {
            return null;
        }

        $tags = $data['tags'];
        unset($data['tags']);

        return is_array($tags) ? $tags : [];
    }

    /**
     * Pisahkan file thumbnail dari array data.
     */
    private function extractThumbnail(array &$data): ?UploadedFile
    {
        $file = $data['thumbnail'] ?? null;
        unset($data['thumbnail']);

        return $file instanceof UploadedFile ? $file : null;
    }
}
