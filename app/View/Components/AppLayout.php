<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(
        public string $title       = '',
        public string $description = '',
        public string $ogImage     = '',
    ) {}

    public function render(): View
    {
        // Mengarah ke resources/views/layouts/app.blade.php
        // Struktur folder layouts tetap tidak berubah
        return view('layouts.app');
    }
}