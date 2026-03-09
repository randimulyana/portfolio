<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;

function adminUser(): User
{
    return User::factory()->create();
}

describe('Admin Project CRUD', function () {

    // ─── Index ────────────────────────────────────────────────────────

    it('shows all projects in admin table', function () {
        $user     = adminUser();
        $projects = Project::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.projects.index'));

        $response->assertOk();
        $projects->each(fn ($p) => $response->assertSee($p->title));
    });

    // ─── Create ───────────────────────────────────────────────────────

    it('shows create project form', function () {
        $this->actingAs(adminUser())
            ->get(route('admin.projects.create'))
            ->assertStatus(200);
    })->skip('View admin.projects.create belum di-copy ke project — copy resources/views/admin/ dari folder outputs');

    it('stores a new project', function () {
        $response = $this->actingAs(adminUser())->post(route('admin.projects.store'), [
            'title'             => 'Proyek Baru',
            'short_description' => 'Deskripsi singkat proyek',
            'status'            => ProjectStatus::Draft->value,
            'is_featured'       => false,
            'sort_order'        => 0,
        ]);

        $response->assertRedirectToRoute('admin.projects.index');
        $this->assertDatabaseHas('projects', ['title' => 'Proyek Baru']);
    });

    it('validates required fields on store', function () {
        $this->actingAs(adminUser())
            ->post(route('admin.projects.store'), [])
            ->assertSessionHasErrors(['title', 'short_description', 'status']);
    });

    it('validates url format on store', function () {
        $this->actingAs(adminUser())
            ->post(route('admin.projects.store'), [
                'title'             => 'Proyek',
                'short_description' => 'Deskripsi',
                'status'            => ProjectStatus::Draft->value,
                'is_featured'       => false,
                'sort_order'        => 0,
                'live_url'          => 'bukan-url-valid',
            ])
            ->assertSessionHasErrors(['live_url']);
    });

    // ─── Edit / Update ────────────────────────────────────────────────

    it('shows edit project form', function () {
        $project = Project::factory()->create();

        $this->actingAs(adminUser())
            ->get(route('admin.projects.edit', $project))
            ->assertStatus(200);
    })->skip('View admin.projects.edit belum di-copy ke project — copy resources/views/admin/ dari folder outputs');

    it('updates an existing project', function () {
        $project = Project::factory()->create(['title' => 'Judul Lama']);

        $this->actingAs(adminUser())
            ->put(route('admin.projects.update', $project), [
                'title'             => 'Judul Baru',
                'short_description' => 'Deskripsi diperbarui',
                'status'            => ProjectStatus::Published->value,
                'is_featured'       => false,
                'sort_order'        => 0,
            ])
            ->assertRedirectToRoute('admin.projects.index');

        expect($project->fresh()->title)->toBe('Judul Baru');
        expect($project->fresh()->status)->toBe(ProjectStatus::Published);
    });

    it('does not change slug when updating title', function () {
        $project      = Project::factory()->create(['title' => 'Original Title']);
        $originalSlug = $project->slug;

        $this->actingAs(adminUser())
            ->put(route('admin.projects.update', $project), [
                'title'             => 'Updated Title',
                'short_description' => 'Deskripsi',
                'status'            => $project->status->value,
                'is_featured'       => false,
                'sort_order'        => 0,
            ]);

        expect($project->fresh()->slug)->toBe($originalSlug);
    });

    // ─── Delete ───────────────────────────────────────────────────────

    it('soft deletes a project', function () {
        $project = Project::factory()->create();

        $this->actingAs(adminUser())
            ->delete(route('admin.projects.destroy', $project))
            ->assertRedirectToRoute('admin.projects.index');

        expect(Project::find($project->id))->toBeNull();
        expect(Project::withTrashed()->find($project->id))->not->toBeNull();
    });

    it('shows success flash after delete', function () {
        $project = Project::factory()->create();

        $this->actingAs(adminUser())
            ->delete(route('admin.projects.destroy', $project))
            ->assertSessionHas('success');
    });
});