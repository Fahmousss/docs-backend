<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PreferenceItem;
use App\Models\PreferenceSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PreferenceItem>
 */
final class PreferenceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'preference_section_id' => PreferenceSection::factory(),
            'item_name'             => fake()->word(),
            'content'               => fake()->realText(),
            'sort_order'            => fake()->numberBetween(1, 100),
        ];
    }
}
