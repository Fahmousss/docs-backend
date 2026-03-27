<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Submenu>
 */
final class SubmenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id'      => Menu::factory(),
            'submenu_name' => fake()->words(3, true),
            'content'      => null,
            'sort_order'   => fake()->numberBetween(1, 100),
        ];
    }
}
