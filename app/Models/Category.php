<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    // ─── Mass assignment ─────────────────────────────────────────────
    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    // ─── Slug config ─────────────────────────────────────────────────
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(); // Jaga URL agar tidak berubah
    }

    // ─── Route model binding via slug ────────────────────────────────
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Relationships ────────────────────────────────────────────────
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // ─── Scope: filter berdasarkan type ──────────────────────────────
    public function scopeForProjects($query)
    {
        return $query->where('type', 'project');
    }

    public function scopeForPosts($query)
    {
        return $query->where('type', 'post');
    }
}
