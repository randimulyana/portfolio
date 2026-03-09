<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // ─── Relasi ─────────────────────────────────────────────
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // ─── Konten utama ────────────────────────────────────────
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('long_description')->nullable();

            // ─── Tech stack disimpan sebagai JSON array ──────────────
            // Contoh: ["Laravel", "Livewire", "TailwindCSS"]
            $table->json('tech_stack')->nullable();

            // ─── Links ──────────────────────────────────────────────
            $table->string('live_url')->nullable();
            $table->string('github_url')->nullable();

            // ─── Pengaturan tampilan ─────────────────────────────────
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();

            // ─── Status — gunakan nilai enum sebagai string ──────────
            $table->string('status', 20)
                  ->default(ProjectStatus::Draft->value)
                  ->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
