<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('updates documentation with nested payload via bulk upsert', function (): void {
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();
    $menuId    = (string) Str::uuid();
    $submenuId = (string) Str::uuid();

    $payload = [
        'sections' => [
            [
                'id'        => $sectionId,
                'name'      => 'Getting Started',
                'sortOrder' => 1,
                'menus'     => [
                    [
                        'id'        => $menuId,
                        'name'      => 'Introduction',
                        'sortOrder' => 1,
                        'submenus'  => [
                            [
                                'id'        => $submenuId,
                                'name'      => 'Welcome',
                                'content'   => '<p>Hello <strong>World</strong></p>',
                                'sortOrder' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), $payload)
        ->assertOk()
        ->assertJsonPath('message', 'Documentation updated successfully');

    // Verify GET returns flattened rows
    getJson(sprintf('/api/v1/products/%s/docs', $product->id))
        ->assertOk()
        ->assertJsonStructure([
            'success', 'message', 'data' => ['items'],
        ])
        ->assertJsonPath('data.items.0.section_name', 'Getting Started')
        ->assertJsonPath('data.items.0.menu_name', 'Introduction')
        ->assertJsonPath('data.items.0.submenu_name', 'Welcome');
});

it('deletes removed items on subsequent updates', function (): void {
    $product = Product::factory()->create();

    // First create tree
    $sectionId = (string) Str::uuid();
    $menuId    = (string) Str::uuid();
    $submenuId = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [[
            'id'        => $sectionId,
            'name'      => 'A',
            'sortOrder' => 1,
            'menus'     => [[
                'id'        => $menuId,
                'name'      => 'B',
                'sortOrder' => 1,
                'submenus'  => [[
                    'id'        => $submenuId,
                    'name'      => 'C',
                    'content'   => null,
                    'sortOrder' => 1,
                ]],
            ]],
        ]],
    ])->assertOk();

    // Now remove all by sending empty sections
    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [],
    ])->assertOk();

    // Expect GET to return empty items
    getJson(sprintf('/api/v1/products/%s/docs', $product->id))
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

it('returns 422 when sections key is missing entirely', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['sections']);
});

it('returns 422 when section id is not a valid uuid', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [
            [
                'id'        => 'not-a-uuid',
                'name'      => 'Section',
                'sortOrder' => 1,
                'menus'     => [],
            ],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['sections.0.id']);
});

it('returns 422 when section is missing required fields', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [
            ['id' => (string) Str::uuid()],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['sections.0.name', 'sections.0.sortOrder', 'sections.0.menus']);
});

it('returns 422 when menu is missing submenus', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Section',
                'sortOrder' => 1,
                'menus'     => [
                    [
                        'id'        => (string) Str::uuid(),
                        'name'      => 'Menu',
                        'sortOrder' => 1,
                        // submenus missing
                    ],
                ],
            ],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['sections.0.menus.0.submenus']);
});

it('returns 422 when submenu sortOrder is a string', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Section',
                'sortOrder' => 1,
                'menus'     => [
                    [
                        'id'        => (string) Str::uuid(),
                        'name'      => 'Menu',
                        'sortOrder' => 1,
                        'submenus'  => [
                            [
                                'id'        => (string) Str::uuid(),
                                'name'      => 'Submenu',
                                'content'   => null,
                                'sortOrder' => 'not-an-integer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['sections.0.menus.0.submenus.0.sortOrder']);
});

it('accepts a valid payload where submenu content is null', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Section',
                'sortOrder' => 1,
                'menus'     => [
                    [
                        'id'        => (string) Str::uuid(),
                        'name'      => 'Menu',
                        'sortOrder' => 1,
                        'submenus'  => [
                            [
                                'id'        => (string) Str::uuid(),
                                'name'      => 'Submenu',
                                'content'   => null,
                                'sortOrder' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ])->assertOk();
});
