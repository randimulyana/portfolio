<?php

use App\Enums\PostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // ─── Relasi ─────────────────────────────────────────────
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // ─── Konten utama ────────────────────────────────────────
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();

            // ─── Status & penjadwalan ────────────────────────────────
            $table->string('status', 20)
                  ->default(PostStatus::Draft->value)
                  ->index();

            // published_at diisi manual saat publish atau scheduled
            $table->timestamp('published_at')->nullable()->index();

            // ─── Statistik ──────────────────────────────────────────
            $table->unsignedBigInteger('views')->default(0);

            // ─── SEO (opsional, diisi di admin) ─────────────────────
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
