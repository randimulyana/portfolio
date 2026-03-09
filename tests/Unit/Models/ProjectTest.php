<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Models\Project;

describe('Project Model', function () {

    // ─── Attributes ──────────────────────────────────────────────────

    it('generates slug from title automatically', function () {
        $project = Project::factory()->create(['title' => 'My Awesome Project']);

        expect($project->slug)->toBe('my-awesome-project');
    });

    it('does not regenerate slug when title is updated', function () {
        $project = Project::factory()->create(['title' => 'Original Title']);
        $originalSlug = $project->slug;

        $project->update(['title' => 'Updated Title']);

        // doNotGenerateSlugsOnUpdate() — slug tetap tidak berubah
        expect($project->fresh()->slug)->toBe($originalSlug);
    });

    it('returns thumbnail url via accessor', function () {
        $project = Project::factory()->create();

        // Tanpa media, harus return string (placeholder atau default)
        expect($project->thumbnail_url)->toBeString();
    });

    it('isPublished returns true for published project', function () {
        $project = Project::factory()->published()->create();

        expect($project->isPublished())->toBeTrue();
    });

    it('isPublished returns false for draft project', function () {
        $project = Project::factory()->draft()->create();

        expect($project->isPublished())->toBeFalse();
    });

    it('casts tech_stack as array', function () {
        $project = Project::factory()->create([
            'tech_stack' => ['Laravel', 'Livewire', 'TailwindCSS'],
        ]);

        expect($project->fresh()->tech_stack)
            ->toBeArray()
            ->toHaveCount(3)
            ->toContain('Laravel');
    });

    it('casts status as ProjectStatus enum', function () {
        $project = Project::factory()->published()->create();

        expect($project->status)->toBeInstanceOf(ProjectStatus::class);
        expect($project->status)->toBe(ProjectStatus::Published);
    });

    // ─── Scopes ──────────────────────────────────────────────────────

    it('published scope returns only published projects', function () {
        Project::factory()->published()->count(3)->create();
        Project::factory()->draft()->count(2)->create();

        $results = Project::published()->get();

        expect($results)->toHaveCount(3);
        expect($results->every(fn ($p) => $p->status === ProjectStatus::Published))->toBeTrue();
    });

    it('featured scope returns only featured projects', function () {
        Project::factory()->published()->featured()->count(2)->create();
        Project::factory()->published()->create(['is_featured' => false]);

        $results = Project::featured()->get();

        expect($results)->toHaveCount(2);
    });

    it('ordered scope sorts by sort_order asc then created_at desc', function () {
        $second = Project::factory()->published()->create(['sort_order' => 2]);
        $first  = Project::factory()->published()->create(['sort_order' => 1]);

        $results = Project::published()->ordered()->get();

        expect($results->first()->id)->toBe($first->id);
        expect($results->last()->id)->toBe($second->id);
    });

    // ─── Soft Delete ─────────────────────────────────────────────────

    it('soft deletes project without removing from database', function () {
        $project = Project::factory()->create();
        $id = $project->id;

        $project->delete();

        expect(Project::find($id))->toBeNull();
        expect(Project::withTrashed()->find($id))->not->toBeNull();
    });

    // ─── Route Key ───────────────────────────────────────────────────

    it('uses slug as route key', function () {
        $project = Project::factory()->create();

        expect($project->getRouteKeyName())->toBe('slug');
    });
});
