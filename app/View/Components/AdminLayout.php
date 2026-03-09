<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public function __construct(
        public string $title = 'Dashboard'
    ) {}

    public function render(): View
    {
        // Mengarah ke resources/views/layouts/admin.blade.php
        // Struktur folder layouts tetap tidak berubah
        return view('layouts.admin');
    }
}