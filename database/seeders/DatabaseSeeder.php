<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShowcaseItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(10)->has(ShowcaseItem::factory(10))->create();
        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@mail.com',
            'password' => 'password',
        ]);
    }
}
