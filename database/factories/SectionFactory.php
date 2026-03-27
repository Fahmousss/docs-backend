<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
final class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'   => Product::factory(),
            'section_name' => fake()->words(3, true),
            'sort_order'   => fake()->numberBetween(1, 100),
        ];
    }
}
