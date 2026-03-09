<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case Draft     = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Published => 'Published',
            self::Scheduled => 'Scheduled',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Draft     => 'bg-amber-100 text-amber-800',
            self::Published => 'bg-emerald-100 text-emerald-800',
            self::Scheduled => 'bg-blue-100 text-blue-800',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
