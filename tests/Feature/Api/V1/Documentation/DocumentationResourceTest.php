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

it('returns documentation items with correct resource structure', function (): void {
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();
    $menuId    = (string) Str::uuid();
    $submenuId = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [[
            'id'        => $sectionId,
            'name'      => 'Installation',
            'sortOrder' => 1,
            'menus'     => [[
                'id'        => $menuId,
                'name'      => 'Requirements',
                'sortOrder' => 1,
                'submenus'  => [[
                    'id'        => $submenuId,
                    'name'      => 'PHP Version',
                    'content'   => '<p>Requires <strong>PHP 8.3+</strong></p>',
                    'sortOrder' => 1,
                ]],
            ]],
        ]],
    ])->assertOk();

    getJson(sprintf('/api/v1/products/%s/docs', $product->id))
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'items' => [
                    '*' => [
                        'product_id',
                        'section_id',
                        'section_name',
                        'section_sort',
                        'menu_id',
                        'menu_name',
                        'menu_sort',
                        'submenu_id',
                        'submenu_name',
                        'content',
                        'submenu_sort',
                    ],
                ],
            ],
        ])
        ->assertJsonPath('data.items.0.product_id', (string) $product->id)
        ->assertJsonPath('data.items.0.section_id', $sectionId)
        ->assertJsonPath('data.items.0.section_name', 'Installation')
        ->assertJsonPath('data.items.0.section_sort', 1)
        ->assertJsonPath('data.items.0.menu_name', 'Requirements')
        ->assertJsonPath('data.items.0.submenu_name', 'PHP Version');
});

it('returns empty items array when no documentation exists', function (): void {
    $product = Product::factory()->create();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [],
    ])->assertOk();

    getJson(sprintf('/api/v1/products/%s/docs', $product->id))
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

it('casts field types correctly in resource output', function (): void {
    $product = Product::factory()->create();

    $sectionId = (string) Str::uuid();
    $menuId    = (string) Str::uuid();
    $submenuId = (string) Str::uuid();

    putJson(sprintf('/api/v1/products/%s/docs', $product->id), [
        'sections' => [[
            'id'        => $sectionId,
            'name'      => 'Section A',
            'sortOrder' => 2,
            'menus'     => [[
                'id'        => $menuId,
                'name'      => 'Menu A',
                'sortOrder' => 3,
                'submenus'  => [[
                    'id'        => $submenuId,
                    'name'      => 'Submenu A',
                    'content'   => null,
                    'sortOrder' => 4,
                ]],
            ]],
        ]],
    ])->assertOk();

    $response = getJson(sprintf('/api/v1/products/%s/docs', $product->id))
        ->assertOk()
        ->json('data.items.0');

    expect($response['section_sort'])->toBeInt()
        ->and($response['menu_sort'])->toBeInt()
        ->and($response['submenu_sort'])->toBeInt()
        ->and($response['product_id'])->toBeString()
        ->and($response['section_id'])->toBeString()
        ->and($response['content'])->toBeNull();
});
