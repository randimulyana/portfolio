<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    /**
     * Buat artikel baru.
     * published_at diset otomatis jika status Published dan belum diisi.
     */
    public function create(array $validatedData): Post
    {
        return DB::transaction(function () use ($validatedData) {
            $tags = $this->extractTags($validatedData);
            $thumbnail = $this->extractThumbnail($validatedData);

            $validatedData = $this->resolvePublishedAt($validatedData);

            $post = $this->postRepository->create($validatedData);

            if ($thumbnail) {
                $post->addMedia($thumbnail)
                     ->toMediaCollection('thumbnail');
            }

            if (!empty($tags)) {
                $post->syncTags($tags);
            }

            return $post;
        });
    }

    /**
     * Update artikel.
     */
    public function update(Post $post, array $validatedData): Post
    {
        return DB::transaction(function () use ($post, $validatedData) {
            $tags = $this->extractTags($validatedData);
            $thumbnail = $this->extractThumbnail($validatedData);

            $validatedData = $this->resolvePublishedAt($validatedData, $post);

            $post = $this->postRepository->update($post, $validatedData);

            if ($thumbnail) {
                $post->clearMediaCollection('thumbnail');
                $post->addMedia($thumbnail)
                     ->toMediaCollection('thumbnail');
            }

            if ($tags !== null) {
                $post->syncTags($tags);
            }

            return $post;
        });
    }

    /**
     * Hapus artikel beserta medianya.
     */
    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $post->clearMediaCollection('thumbnail');
            $this->postRepository->delete($post);
        });
    }

    /**
     * Publish artikel — set status Published dan published_at = now().
     */
    public function publish(Post $post): Post
    {
        return $this->postRepository->update($post, [
            'status'       => PostStatus::Published,
            'published_at' => $post->published_at ?? now(),
        ]);
    }

    /**
     * Kembalikan ke Draft.
     */
    public function unpublish(Post $post): Post
    {
        return $this->postRepository->update($post, [
            'status' => PostStatus::Draft,
        ]);
    }

    // ─── Private helpers ──────────────────────────────────────────────

    /**
     * Atur published_at secara otomatis:
     * - Jika status Published dan published_at kosong → set ke now()
     * - Jika status Draft → biarkan null (jangan hapus yang sudah ada)
     */
    private function resolvePublishedAt(array $data, ?Post $existing = null): array
    {
        $status = PostStatus::tryFrom($data['status'] ?? '');

        if ($status === PostStatus::Published && empty($data['published_at'])) {
            // Pakai published_at lama jika ada (re-publish), kalau tidak pakai now()
            $data['published_at'] = $existing?->published_at ?? now();
        }

        return $data;
    }

    private function extractTags(array &$data): ?array
    {
        if (! array_key_exists('tags', $data)) {
            return null;
        }

        $tags = $data['tags'];
        unset($data['tags']);

        return is_array($tags) ? $tags : [];
    }

    private function extractThumbnail(array &$data): ?UploadedFile
    {
        $file = $data['thumbnail'] ?? null;
        unset($data['thumbnail']);

        return $file instanceof UploadedFile ? $file : null;
    }
}
