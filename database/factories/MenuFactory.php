<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
final class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'menu_name'  => fake()->words(2, true),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}
