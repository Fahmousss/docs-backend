<?php

declare(strict_types=1);

use App\Models\Menu;
use App\Models\Product;
use App\Models\Section;
use App\Models\Submenu;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

it('returns an empty sections array for a product with no sections', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    getJson(sprintf('/api/v1/products/%s/docs/navigation', $product->id))
        ->assertOk()
        ->assertJsonPath('data.sections', [])
        ->assertJsonPath('message', 'Navigation retrieved successfully');
});

it('returns a nested section, menu, submenu tree without content', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Section $section */
    $section = Section::factory()->create([
        'product_id'   => $product->id,
        'section_name' => 'Getting Started',
        'sort_order'   => 1,
    ]);

    /** @var Menu $menu */
    $menu = Menu::factory()->create([
        'section_id' => $section->id,
        'menu_name'  => 'Introduction',
        'sort_order' => 1,
    ]);

    Submenu::factory()->create([
        'menu_id'      => $menu->id,
        'submenu_name' => 'Welcome',
        'content'      => '<p>Secret content</p>',
        'sort_order'   => 1,
    ]);

    $response = getJson(sprintf('/api/v1/products/%s/docs/navigation', $product->id))
        ->assertOk()
        ->assertJsonPath('data.sections.0.section_name', 'Getting Started')
        ->assertJsonPath('data.sections.0.menus.0.menu_name', 'Introduction')
        ->assertJsonPath('data.sections.0.menus.0.submenus.0.submenu_name', 'Welcome');

    $submenu = $response->json('data.sections.0.menus.0.submenus.0');
    expect($submenu)->not->toHaveKey('content');
});

it('returns sections ordered by sort_order', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    Section::factory()->create(['product_id' => $product->id, 'section_name' => 'B', 'sort_order' => 2]);
    Section::factory()->create(['product_id' => $product->id, 'section_name' => 'A', 'sort_order' => 1]);

    getJson(sprintf('/api/v1/products/%s/docs/navigation', $product->id))
        ->assertOk()
        ->assertJsonPath('data.sections.0.section_name', 'A')
        ->assertJsonPath('data.sections.1.section_name', 'B');
});

it('returns correct nested structure with multiple menus and submenus', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Section $section */
    $section = Section::factory()->create(['product_id' => $product->id, 'sort_order' => 1]);

    /** @var Menu $menuOne */
    $menuOne = Menu::factory()->create(['section_id' => $section->id, 'sort_order' => 1]);
    /** @var Menu $menuTwo */
    $menuTwo = Menu::factory()->create(['section_id' => $section->id, 'sort_order' => 2]);

    Submenu::factory()->create(['menu_id' => $menuOne->id, 'sort_order' => 1]);
    Submenu::factory()->create(['menu_id' => $menuOne->id, 'sort_order' => 2]);
    Submenu::factory()->create(['menu_id' => $menuTwo->id, 'sort_order' => 1]);

    getJson(sprintf('/api/v1/products/%s/docs/navigation', $product->id))
        ->assertOk()
        ->assertJsonCount(1, 'data.sections')
        ->assertJsonCount(2, 'data.sections.0.menus')
        ->assertJsonCount(2, 'data.sections.0.menus.0.submenus')
        ->assertJsonCount(1, 'data.sections.0.menus.1.submenus');
});

it('does not require authentication', function (): void {
    /** @var Product $product */
    $product = Product::factory()->create();

    getJson(sprintf('/api/v1/products/%s/docs/navigation', $product->id))
        ->assertOk();
});
