<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ShowcaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShowcaseItem>
 */
final class ShowcaseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'  => Product::factory(),
            'title'       => fake()->sentence(),
            'description' => fake()->sentence(),
            'media_url'   => fake()->imageUrl(),
            'content'     => fake()->realText(1000),
            'sort_order'  => fake()->numberBetween(1, 100),
        ];
    }
}
