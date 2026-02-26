<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BlogSection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogSection>
 */
final class BlogSectionFactory extends Factory
{
    protected $model = BlogSection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'     => Product::factory(),
            'title'          => fake()->sentence(),
            'publish_date'   => fake()->date(),
            'description'    => fake()->paragraph(),
            'content'        => '<p>'.fake()->paragraphs(3, true).'</p>',
            'hero_image_url' => fake()->imageUrl(),
            'creators'       => [
                [
                    'name'     => fake()->name(),
                    'photoUrl' => fake()->imageUrl(),
                ],
            ],
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
