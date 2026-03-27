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

it('updates and retrieves showcase items', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    $itemId1 = (string) Str::uuid();
    $itemId2 = (string) Str::uuid();

    $payload = [
        'items' => [
            [
                'id'          => $itemId1,
                'title'       => 'Feature 1',
                'description' => 'First feature',
                'mediaUrl'    => 'https://cdn.example.com/1.jpg',
                'content'     => '<p>Rich text 1</p>',
                'sortOrder'   => 1,
            ],
            [
                'id'          => $itemId2,
                'title'       => 'Feature 2',
                'description' => 'Second feature',
                'mediaUrl'    => 'https://cdn.example.com/2.jpg',
                'content'     => '<p>Rich text 2</p>',
                'sortOrder'   => 2,
            ],
        ],
    ];

    $response = putJson(sprintf('/api/v1/products/%s/showcase', $product->id), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Showcase updated successfully',
        ]);

    assertDatabaseHas('showcase_items', [
        'id'         => $itemId1,
        'title'      => 'Feature 1',
        'sort_order' => 1,
    ]);

    assertDatabaseHas('showcase_items', [
        'id'         => $itemId2,
        'title'      => 'Feature 2',
        'sort_order' => 2,
    ]);

    // Test Retrieval
    $getResp = getJson(sprintf('/api/v1/products/%s/showcase', $product->id));

    $getResp->assertOk()
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.items.0.id', $itemId1)
        ->assertJsonPath('data.items.0.title', 'Feature 1')
        ->assertJsonPath('data.items.1.id', $itemId2)
        ->assertJsonPath('data.items.1.title', 'Feature 2');
});

it('performs a full update and deletes missing items', function (): void {
    $product = Product::factory()->create();

    $itemId1 = (string) Str::uuid();
    $itemId2 = (string) Str::uuid();

    // Initial sync
    putJson(sprintf('/api/v1/products/%s/showcase', $product->id), [
        'items' => [
            ['id' => $itemId1, 'title' => 'Initial 1', 'sortOrder' => 1],
            ['id' => $itemId2, 'title' => 'Initial 2', 'sortOrder' => 2],
        ],
    ])->assertOk();

    // Second sync: omit itemId1, update itemId2
    putJson(sprintf('/api/v1/products/%s/showcase', $product->id), [
        'items' => [
            ['id' => $itemId2, 'title' => 'Updated 2', 'sortOrder' => 2],
        ],
    ])->assertOk();

    // Verify itemId1 was deleted and itemId2 was updated
    assertDatabaseMissing('showcase_items', ['id' => $itemId1]);
    assertDatabaseHas('showcase_items', ['id' => $itemId2, 'title' => 'Updated 2']);
});

it('sanitizes showcase content', function (): void {
    $product = Product::factory()->create();
    $itemId  = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/showcase', $product->id), [
        'items' => [
            [
                'id'        => $itemId,
                'title'     => 'Title',
                'content'   => '<p><script>alert("xss")</script>Safe Content</p>',
                'sortOrder' => 1,
            ],
        ],
    ])->assertOk();

    assertDatabaseMissing('showcase_items', [
        'content' => '<p><script>alert("xss")</script>Safe Content</p>',
    ]);

    assertDatabaseHas('showcase_items', [
        'id'      => $itemId,
        'content' => '<p>Safe Content</p>',
    ]);
});
