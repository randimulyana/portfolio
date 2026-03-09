<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Project;

describe('Projects Page (Public)', function () {

    // ─── Index ───────────────────────────────────────────────────────

    it('shows projects index page', function () {
        $this->get(route('projects.index'))->assertOk();
    });

    it('shows only published projects', function () {
        $published = Project::factory()->published()->count(3)->create();
        $draft     = Project::factory()->draft()->create();

        $response = $this->get(route('projects.index'));

        $published->each(fn ($p) => $response->assertSee($p->title));
        $response->assertDontSee($draft->title);
    });

    it('filters projects by category', function () {
        $category = Category::factory()->projectType()->create();
        $inCategory  = Project::factory()->published()->create(['category_id' => $category->id]);
        $outCategory = Project::factory()->published()->create();

        $response = $this->get(route('projects.index', ['category' => $category->slug]));

        $response->assertSee($inCategory->title);
        $response->assertDontSee($outCategory->title);
    });

    it('shows all projects when no category filter', function () {
        Project::factory()->published()->count(4)->create();

        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertSee('Proyek');
    });

    // ─── Show ────────────────────────────────────────────────────────

    it('shows project detail page', function () {
        $project = Project::factory()->published()->create([
            'short_description' => 'Deskripsi proyek ini',
        ]);

        $response = $this->get(route('projects.show', $project->slug));

        $response->assertOk();
        $response->assertSee($project->title);
        $response->assertSee($project->short_description);
    });

    it('returns 404 for draft project', function () {
        $draft = Project::factory()->draft()->create();

        $this->get(route('projects.show', $draft->slug))->assertNotFound();
    });

    it('returns 404 for non-existent project', function () {
        $this->get(route('projects.show', 'proyek-tidak-ada'))->assertNotFound();
    });

    it('shows tech stack on project detail', function () {
        $project = Project::factory()->published()->create([
            'tech_stack' => ['Laravel', 'MySQL'],
        ]);

        $response = $this->get(route('projects.show', $project->slug));

        $response->assertSee('Laravel');
        $response->assertSee('MySQL');
    });

    it('shows live url link when available', function () {
        $project = Project::factory()->published()->create([
            'live_url' => 'https://example.com',
        ]);

        $this->get(route('projects.show', $project->slug))
            ->assertSee('https://example.com');
    });
});
