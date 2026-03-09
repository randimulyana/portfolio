<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
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

class Post extends Model implements HasMedia
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
        'content',
        'excerpt',
        'status',
        'published_at',
        'views',
        'meta_title',
        'meta_description',
    ];

    // ─── Casts ────────────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'status'       => PostStatus::class,
            'published_at' => 'datetime',
            'views'        => 'integer',
        ];
    }

    // ─── Slug config ─────────────────────────────────────────────────
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Media collections ────────────────────────────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
             ->singleFile()
             ->useFallbackUrl('/images/post-placeholder.webp');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(800)
             ->height(450)   // Rasio 16:9
             ->sharpen(5)
             ->nonQueued();

        $this->addMediaConversion('og')
             ->width(1200)
             ->height(630)   // Rasio Open Graph
             ->nonQueued();
    }

    // ─── Relationships ────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ─── Query Scopes ─────────────────────────────────────────────────
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    // ─── Accessor helpers ─────────────────────────────────────────────
    public function isPublished(): bool
    {
        return $this->status === PostStatus::Published
            && $this->published_at?->isPast();
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail', 'thumb')
            ?: '/images/post-placeholder.webp';
    }

    /**
     * Estimasi waktu baca berdasarkan jumlah kata.
     * Rata-rata orang membaca 200 kata/menit.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));

        return (int) max(1, ceil($wordCount / 200));
    }

    /**
     * Tambah view count — dipanggil dari controller, bukan observer,
     * agar mudah dikontrol dan di-test.
     */
    public function incrementViews(): void
    {
        $this->incrementQuietly('views'); // Tidak trigger events/timestamps
    }

    /**
     * Ambil excerpt: pakai kolom excerpt jika ada,
     * fallback ke 160 karakter pertama dari content.
     */
    public function getExcerptTextAttribute(): string
    {
        return $this->excerpt
            ?? str($this->content ?? '')->stripTags()->limit(160)->toString();
    }
}
