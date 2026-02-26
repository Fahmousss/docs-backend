<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);
});

it('updates and retrieves a products blog sections', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    $sectionId1 = (string) Str::uuid();
    $sectionId2 = (string) Str::uuid();

    $payload = [
        'sections' => [
            [
                'id'           => $sectionId1,
                'title'        => 'First Blog Post',
                'publishDate'  => '2023-10-01',
                'description'  => 'A short summary',
                'content'      => '<p>Hello world</p>',
                'heroImageUrl' => 'https://example.com/img1.jpg',
                'creators'     => [
                    ['name' => 'Alice', 'photoUrl' => 'https://example.com/alice.jpg'],
                ],
                'sortOrder' => 1,
            ],
            [
                'id'           => $sectionId2,
                'title'        => 'Second Blog Post',
                'publishDate'  => '2023-11-01',
                'description'  => 'Another summary',
                'content'      => '<p>Foobar</p>',
                'heroImageUrl' => null,
                'creators'     => [],
                'sortOrder'    => 2,
            ],
        ],
    ];

    $response = putJson(sprintf('/api/v1/products/%s/blog', $product->id), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Blog updated successfully',
        ]);

    assertDatabaseHas('blog_sections', [
        'id'    => $sectionId1,
        'title' => 'First Blog Post',
    ]);

    assertDatabaseHas('blog_sections', [
        'id'    => $sectionId2,
        'title' => 'Second Blog Post',
    ]);

    // Test Retrieval formatting from materialized view schema
    $getResp = getJson(sprintf('/api/v1/products/%s/blog', $product->id));

    $getResp->assertOk()
        ->assertJsonCount(2, 'data.sections')
        ->assertJsonPath('data.sections.0.id', $sectionId1)
        ->assertJsonPath('data.sections.0.title', 'First Blog Post')
        ->assertJsonPath('data.sections.0.publishDate', '2023-10-01')
        ->assertJsonPath('data.sections.0.creators.0.name', 'Alice')
        ->assertJsonPath('data.sections.1.id', $sectionId2)
        ->assertJsonPath('data.sections.1.creators', []);
});

it('performs a bulk sync and deletes removed sections', function (): void {
    $product = Product::factory()->create();

    $sectionId1 = (string) Str::uuid();
    $sectionId2 = (string) Str::uuid();

    // Initial sync
    putJson(sprintf('/api/v1/products/%s/blog', $product->id), [
        'sections' => [
            ['id' => $sectionId1, 'title' => 'Post 1', 'publishDate' => '2024-01-01', 'description' => '', 'content' => '', 'heroImageUrl' => null, 'creators' => [], 'sortOrder' => 1],
            ['id' => $sectionId2, 'title' => 'Post 2', 'publishDate' => '2024-01-02', 'description' => '', 'content' => '', 'heroImageUrl' => null, 'creators' => [], 'sortOrder' => 2],
        ],
    ])->assertOk();

    // Second sync: omit Section 1 entirely, keep Section 2, add Section 3
    $sectionId3 = (string) Str::uuid();
    putJson(sprintf('/api/v1/products/%s/blog', $product->id), [
        'sections' => [
            ['id' => $sectionId2, 'title' => 'Post 2 Updated', 'publishDate' => '2024-01-02', 'description' => '', 'content' => '', 'heroImageUrl' => null, 'creators' => [], 'sortOrder' => 1],
            ['id' => $sectionId3, 'title' => 'Post 3', 'publishDate' => '2024-01-03', 'description' => '', 'content' => '', 'heroImageUrl' => null, 'creators' => [], 'sortOrder' => 2],
        ],
    ])->assertOk();

    // Verify
    assertDatabaseMissing('blog_sections', ['id' => $sectionId1]);
    assertDatabaseHas('blog_sections', ['id' => $sectionId2, 'title' => 'Post 2 Updated']);
    assertDatabaseHas('blog_sections', ['id' => $sectionId3, 'title' => 'Post 3']);
});

it('sanitizes blog content', function (): void {
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/blog', $product->id), [
        'sections' => [
            [
                'id'           => $sectionId,
                'title'        => 'Hacked Post',
                'publishDate'  => '2024-01-01',
                'description'  => 'Hack summary',
                'content'      => '<p><script>alert("xss")</script>Safe HTML</p>',
                'heroImageUrl' => null,
                'creators'     => [],
                'sortOrder'    => 1,
            ],
        ],
    ])->assertOk();

    assertDatabaseMissing('blog_sections', [
        'content' => '<p><script>alert("xss")</script>Safe HTML</p>',
    ]);

    assertDatabaseHas('blog_sections', [
        'id'      => $sectionId,
        'content' => '<p>Safe HTML</p>',
    ]);
});
