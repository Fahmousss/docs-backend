<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PreferenceSection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PreferenceSection>
 */
final class PreferenceSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name'       => fake()->word(),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}
