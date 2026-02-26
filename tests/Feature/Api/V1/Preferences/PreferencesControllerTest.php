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

it('updates and retrieves product preferences setup', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();
    $itemId1   = (string) Str::uuid();
    $itemId2   = (string) Str::uuid();

    $payload = [
        'sections' => [
            [
                'id'        => $sectionId,
                'name'      => 'General Setup',
                'sortOrder' => 1,
                'items'     => [
                    [
                        'id'        => $itemId1,
                        'itemName'  => 'Main Info',
                        'content'   => '<p>Acme Corp</p>',
                        'sortOrder' => 1,
                    ],
                    [
                        'id'        => $itemId2,
                        'itemName'  => 'Legal',
                        'content'   => '<p>Terms apply</p>',
                        'sortOrder' => 2,
                    ],
                ],
            ],
        ],
    ];

    $response = putJson(sprintf('/api/v1/products/%s/preferences', $product->id), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Preferences updated successfully',
        ]);

    assertDatabaseHas('preference_sections', [
        'id'   => $sectionId,
        'name' => 'General Setup',
    ]);

    assertDatabaseHas('preference_items', [
        'id'        => $itemId1,
        'item_name' => 'Main Info',
    ]);

    assertDatabaseHas('preference_items', [
        'id'        => $itemId2,
        'item_name' => 'Legal',
    ]);

    // Test Retrieval formatting from materialized view schema
    $getResp = getJson(sprintf('/api/v1/products/%s/preferences', $product->id));

    $getResp->assertOk()
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.items.0.section_id', $sectionId)
        ->assertJsonPath('data.items.0.item_id', $itemId1)
        ->assertJsonPath('data.items.0.item_name', 'Main Info')
        ->assertJsonPath('data.items.1.item_id', $itemId2)
        ->assertJsonPath('data.items.1.item_name', 'Legal');
});

it('performs a partial update without deleting missing sections or items', function (): void {
    $product = Product::factory()->create();

    $sectionId1 = (string) Str::uuid();
    $sectionId2 = (string) Str::uuid();
    $itemId1    = (string) Str::uuid();
    $itemId2    = (string) Str::uuid();

    // Initial sync
    putJson(sprintf('/api/v1/products/%s/preferences', $product->id), [
        'sections' => [
            [
                'id'        => $sectionId1,
                'name'      => 'Section 1',
                'sortOrder' => 1,
                'items'     => [
                    ['id' => $itemId1, 'itemName' => 'Tab 1', 'content' => '', 'sortOrder' => 1],
                ],
            ],
            [
                'id'        => $sectionId2,
                'name'      => 'Section 2',
                'sortOrder' => 2,
                'items'     => [
                    ['id' => $itemId2, 'itemName' => 'Tab 2', 'content' => '', 'sortOrder' => 1],
                ],
            ],
        ],
    ])->assertOk();

    // Second sync: omit Section 1 entirely, only send updated Section 2 but with a NEW itemId (meaning old one should be kept)
    $newItemId = (string) Str::uuid();
    putJson(sprintf('/api/v1/products/%s/preferences', $product->id), [
        'sections' => [
            [
                'id'        => $sectionId2,
                'name'      => 'Section 2 - Updated',
                'sortOrder' => 2,
                'items'     => [
                    ['id' => $newItemId, 'itemName' => 'Tab 2 New', 'content' => '', 'sortOrder' => 2],
                ],
            ],
        ],
    ])->assertOk();

    // Verify both sections still exist
    assertDatabaseHas('preference_sections', ['id' => $sectionId1, 'name' => 'Section 1']);
    assertDatabaseHas('preference_sections', ['id' => $sectionId2, 'name' => 'Section 2 - Updated']);

    // Verify all 3 items exist
    assertDatabaseHas('preference_items', ['id' => $itemId1, 'item_name' => 'Tab 1']);
    assertDatabaseHas('preference_items', ['id' => $itemId2, 'item_name' => 'Tab 2']);
    assertDatabaseHas('preference_items', ['id' => $newItemId, 'item_name' => 'Tab 2 New']);
});

it('sanitizes item content', function (): void {
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();
    $itemId    = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/preferences', $product->id), [
        'sections' => [
            [
                'id'        => $sectionId,
                'name'      => 'Sec',
                'sortOrder' => 1,
                'items'     => [
                    [
                        'id'        => $itemId,
                        'itemName'  => 'Tab',
                        'content'   => '<p><script>alert("xss")</script>Safe HTML</p>',
                        'sortOrder' => 1,
                    ],
                ],
            ],
        ],
    ])->assertOk();

    assertDatabaseMissing('preference_items', [
        'content' => '<p><script>alert("xss")</script>Safe HTML</p>',
    ]);

    assertDatabaseHas('preference_items', [
        'id'      => $itemId,
        'content' => '<p>Safe HTML</p>',
    ]);
});
