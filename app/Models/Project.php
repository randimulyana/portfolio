<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Project extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;

    // ─── Mass assignment ─────────────────────────────────────────────
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'long_description',
        'tech_stack',
        'live_url',
        'github_url',
        'is_featured',
        'sort_order',
        'status',
    ];

    // ─── Casts ────────────────────────────────────────────────────────
    // Semua tipe data eksplisit — tidak ada ambiguitas
    protected function casts(): array
    {
        return [
            'tech_stack'  => 'array',          // JSON ↔ PHP array
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'status'      => ProjectStatus::class, // string ↔ Enum
        ];
    }

    // ─── Slug config ─────────────────────────────────────────────────
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(); // URL tidak berubah saat edit judul
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Media collections (Spatie Media Library) ────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
             ->singleFile()                   // Hanya 1 thumbnail per project
             ->useFallbackUrl('/images/project-placeholder.webp')
             ->useFallbackPath(public_path('/images/project-placeholder.webp'));

        $this->addMediaCollection('screenshots'); // Boleh banyak
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Auto-generate ukuran thumbnail yang dioptimasi
        $this->addMediaConversion('thumb')
             ->width(600)
             ->height(400)
             ->sharpen(5)
             ->nonQueued();

        $this->addMediaConversion('card')
             ->width(400)
             ->height(280)
             ->nonQueued();
    }

    // ─── Relationships ────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ─── Query Scopes ─────────────────────────────────────────────────
    // Penggunaan: Project::published()->get()
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Published);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    // ─── Accessor helpers ─────────────────────────────────────────────
    public function isPublished(): bool
    {
        return $this->status === ProjectStatus::Published;
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail', 'thumb')
            ?: '/images/project-placeholder.webp';
    }
}
