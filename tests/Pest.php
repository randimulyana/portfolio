<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Pest Configuration
|--------------------------------------------------------------------------
|
| RefreshDatabase di-apply ke semua test secara global.
| Setiap test berjalan dalam transaction yang di-rollback,
| sehingga DB selalu bersih tanpa perlu truncate manual.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Custom Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBePublished', function () {
    return $this->status->value === 'published';
});

expect()->extend('toBeDraft', function () {
    return $this->status->value === 'draft';
});
