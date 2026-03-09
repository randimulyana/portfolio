<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Services\ProjectService;

// Helper untuk buat service dengan repository nyata
function makeProjectService(): ProjectService
{
    return new ProjectService(new ProjectRepository(new Project));
}

describe('ProjectService', function () {

    // ─── Create ──────────────────────────────────────────────────────

    it('creates a project with valid data', function () {
        $data = [
            'title'             => 'Test Proyek',
            'short_description' => 'Deskripsi singkat',
            'status'            => ProjectStatus::Draft->value,
            'sort_order'        => 0,
            'is_featured'       => false,
        ];

        $project = makeProjectService()->create($data);

        expect($project)->toBeInstanceOf(Project::class);
        expect($project->title)->toBe('Test Proyek');
        expect($project->slug)->toBe('test-proyek');
        expect(Project::count())->toBe(1);
    });

    it('creates project with tags', function () {
        $data = [
            'title'             => 'Proyek Bertag',
            'short_description' => 'Deskripsi',
            'status'            => ProjectStatus::Draft->value,
            'sort_order'        => 0,
            'is_featured'       => false,
            'tags'              => ['Laravel', 'Livewire'],
        ];

        $project = makeProjectService()->create($data);

        expect($project->tags)->toHaveCount(2);
        expect($project->tags->pluck('name')->toArray())->toContain('Laravel');
    });

    it('does not include tags in project fillable fields', function () {
        // Tags tidak boleh masuk kolom projects — harus ke tabel pivot
        $data = [
            'title'             => 'Proyek Clean',
            'short_description' => 'Deskripsi',
            'status'            => ProjectStatus::Draft->value,
            'sort_order'        => 0,
            'is_featured'       => false,
            'tags'              => ['Vue.js'],
        ];

        $project = makeProjectService()->create($data);

        // Tidak ada kolom 'tags' di tabel projects
        expect(array_keys($project->getAttributes()))->not->toContain('tags');
    });

    // ─── Update ──────────────────────────────────────────────────────

    it('updates project data', function () {
        $project = Project::factory()->create(['title' => 'Judul Lama']);

        makeProjectService()->update($project, [
            'title'             => 'Judul Baru',
            'short_description' => $project->short_description,
            'status'            => $project->status->value,
            'sort_order'        => 0,
            'is_featured'       => false,
        ]);

        expect($project->fresh()->title)->toBe('Judul Baru');
    });

    it('does not change slug when title is updated', function () {
        $project = Project::factory()->create(['title' => 'Original']);
        $originalSlug = $project->slug;

        makeProjectService()->update($project, [
            'title'             => 'Judul Berubah',
            'short_description' => $project->short_description,
            'status'            => $project->status->value,
            'sort_order'        => 0,
            'is_featured'       => false,
        ]);

        expect($project->fresh()->slug)->toBe($originalSlug);
    });

    // ─── Delete ──────────────────────────────────────────────────────

    it('soft deletes a project', function () {
        $project = Project::factory()->create();
        $id = $project->id;

        makeProjectService()->delete($project);

        expect(Project::find($id))->toBeNull();
        expect(Project::withTrashed()->find($id))->not->toBeNull();
    });

    // ─── Toggle Status ───────────────────────────────────────────────

    it('toggles status from draft to published', function () {
        $project = Project::factory()->draft()->create();

        $updated = makeProjectService()->toggleStatus($project);

        expect($updated->status)->toBe(ProjectStatus::Published);
    });

    it('toggles status from published to draft', function () {
        $project = Project::factory()->published()->create();

        $updated = makeProjectService()->toggleStatus($project);

        expect($updated->status)->toBe(ProjectStatus::Draft);
    });

    // ─── Toggle Featured ─────────────────────────────────────────────

    it('toggles featured on', function () {
        $project = Project::factory()->create(['is_featured' => false]);

        $updated = makeProjectService()->toggleFeatured($project);

        expect($updated->is_featured)->toBeTrue();
    });

    it('toggles featured off', function () {
        $project = Project::factory()->featured()->published()->create();

        $updated = makeProjectService()->toggleFeatured($project);

        expect($updated->is_featured)->toBeFalse();
    });
});
