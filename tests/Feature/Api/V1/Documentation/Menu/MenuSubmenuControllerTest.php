<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Documentation\Menu;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Section;
use App\Models\Submenu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can fetch submenus by menu and product', function () {
    $product = Product::factory()->create();
    $section = Section::factory()->create(['product_id' => $product->id]);
    $menu    = Menu::factory()->create(['section_id' => $section->id]);

    $submenus = Submenu::factory(3)->create([
        'menu_id' => $menu->id,
    ]);

    // Add submenu to a different menu to ensure it isn't returned
    Submenu::factory()->create();

    $response = $this->getJson(route('api.v1.products.docs.menus.submenus', [
        'productId' => $product->id,
        'menuId'    => $menu->id,
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'submenu_name',
                    'content',
                    'sort_order',
                ],
            ],
        ]);

    $responseData = $response->json('data');

    // Make sure correct submenus are fetched
    $fetchedIds = collect($responseData)->pluck('id')->toArray();
    foreach ($submenus as $submenu) {
        expect($fetchedIds)->toContain($submenu->id);
    }
});

test('submenus are sorted by sort_order', function () {
    $product = Product::factory()->create();
    $section = Section::factory()->create(['product_id' => $product->id]);
    $menu    = Menu::factory()->create(['section_id' => $section->id]);

    Submenu::factory()->create(['menu_id' => $menu->id, 'sort_order' => 3]);
    Submenu::factory()->create(['menu_id' => $menu->id, 'sort_order' => 1]);
    Submenu::factory()->create(['menu_id' => $menu->id, 'sort_order' => 2]);

    $response = $this->getJson(route('api.v1.products.docs.menus.submenus', [
        'productId' => $product->id,
        'menuId'    => $menu->id,
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');

    $responseData = $response->json('data');

    expect($responseData[0]['sort_order'])->toBe(1)
        ->and($responseData[1]['sort_order'])->toBe(2)
        ->and($responseData[2]['sort_order'])->toBe(3);
});

test('returns empty data when no submenus belong to the menu', function () {
    $product = Product::factory()->create();
    $section = Section::factory()->create(['product_id' => $product->id]);
    $menu    = Menu::factory()->create(['section_id' => $section->id]);

    // Submenu belonging to another menu
    Submenu::factory()->create();

    $response = $this->getJson(route('api.v1.products.docs.menus.submenus', [
        'productId' => $product->id,
        'menuId'    => $menu->id,
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data')
        ->assertExactJson([
            'success' => true,
            'message' => 'Submenus retrieved successfully',
            'data'    => [],
        ]);
});

test('returns empty data when product mismatch', function () {
    $product1 = Product::factory()->create();
    $section1 = Section::factory()->create(['product_id' => $product1->id]);
    $menu1    = Menu::factory()->create(['section_id' => $section1->id]);
    Submenu::factory()->create(['menu_id' => $menu1->id]);

    $product2 = Product::factory()->create();

    $response = $this->getJson(route('api.v1.products.docs.menus.submenus', [
        'productId' => $product2->id,
        'menuId'    => $menu1->id, // Menu belongs to product1, but requested product2
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});
