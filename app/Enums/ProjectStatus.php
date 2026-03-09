<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectStatus: string
{
    case Draft     = 'draft';
    case Published = 'published';

    // ─── Label untuk tampilan UI ────────────────────────────
    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Published => 'Published',
        };
    }

    // ─── Warna badge Tailwind ────────────────────────────────
    public function badgeClass(): string
    {
        return match($this) {
            self::Draft     => 'bg-amber-100 text-amber-800',
            self::Published => 'bg-emerald-100 text-emerald-800',
        };
    }

    // ─── Semua nilai sebagai array (untuk select option) ────
    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
