<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Project;
use App\Repositories\ProjectRepository;

function projectRepo(): ProjectRepository
{
    return new ProjectRepository(new Project);
}

describe('ProjectRepository', function () {

    // allPublished() → returns Collection (BUKAN paginator, BUKAN paginatePublished)
    it('returns all published projects as collection', function () {
        Project::factory()->published()->count(5)->create();
        Project::factory()->draft()->count(3)->create();

        $result = projectRepo()->allPublished();

        expect($result)->toHaveCount(5);
        expect($result->every(fn ($p) => $p->isPublished()))->toBeTrue();
    });

    it('filters published projects by category slug', function () {
        $category = Category::factory()->projectType()->create();
        Project::factory()->published()->count(2)->create(['category_id' => $category->id]);
        Project::factory()->published()->count(3)->create(); // tanpa kategori ini

        $result = projectRepo()->allPublished(categorySlug: $category->slug);

        expect($result)->toHaveCount(2);
    });

    // findBySlug() → firstOrFail() → melempar exception jika tidak ada
    it('finds project by slug', function () {
        $project = Project::factory()->published()->create();

        $found = projectRepo()->findBySlug($project->slug);

        expect($found)->not->toBeNull();
        expect($found->id)->toBe($project->id);
    });

    it('throws ModelNotFoundException for non-existent slug', function () {
        // findBySlug() pakai firstOrFail() — melempar exception, BUKAN return null
        expect(fn () => projectRepo()->findBySlug('slug-tidak-ada'))
            ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    it('throws ModelNotFoundException for draft project via findBySlug', function () {
        $draft = Project::factory()->draft()->create();

        // scope published() tidak lolos untuk draft
        expect(fn () => projectRepo()->findBySlug($draft->slug))
            ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    // getFeatured(int $limit) — featured published projects
    it('returns only featured published projects', function () {
        Project::factory()->published()->featured()->count(3)->create();
        Project::factory()->published()->create(['is_featured' => false]);
        Project::factory()->draft()->featured()->create();

        $result = projectRepo()->getFeatured();

        expect($result)->toHaveCount(3);
        expect($result->every(fn ($p) => $p->is_featured && $p->isPublished()))->toBeTrue();
    });

    it('getFeatured respects limit', function () {
        Project::factory()->published()->featured()->count(5)->create();

        $result = projectRepo()->getFeatured(limit: 2);

        expect($result)->toHaveCount(2);
    });

    // paginateForAdmin() — untuk tabel admin
    it('paginates all projects for admin', function () {
        Project::factory()->count(20)->create();

        $result = projectRepo()->paginateForAdmin(perPage: 10);

        expect($result->count())->toBe(10);
        expect($result->total())->toBe(20);
    });

    it('paginateForAdmin searches by title', function () {
        Project::factory()->create(['title' => 'Laravel Portfolio']);
        Project::factory()->create(['title' => 'Vue Dashboard']);
        Project::factory()->count(3)->create();

        $result = projectRepo()->paginateForAdmin(search: 'Laravel');

        expect($result->total())->toBe(1);
        expect($result->first()->title)->toBe('Laravel Portfolio');
    });
});
