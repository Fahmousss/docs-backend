<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can list all products ordered by name', function (): void {
    Product::factory()->create(['name' => 'Z Product']);
    Product::factory()->create(['name' => 'A Product']);
    Product::factory()->create(['name' => 'M Product']);

    getJson('/api/v1/products')
        ->assertSuccessful()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.0.name', 'A Product')
        ->assertJsonPath('data.1.name', 'M Product')
        ->assertJsonPath('data.2.name', 'Z Product');
});

it('can show a product by id', function (): void {
    $product = Product::factory()->create(['name' => 'Test Product']);

    getJson('/api/v1/products/'.$product->id)
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Test Product');
});

it('throws 404 if product not found on show', function (): void {
    getJson('/api/v1/products/999')
        ->assertNotFound();
});

it('can create a product', function (): void {
    $data = ['name' => 'New Product'];

    postJson('/api/v1/products', $data)
        ->assertCreated()
        ->assertJsonPath('data.name', 'New Product');

    assertDatabaseHas('products', ['name' => 'New Product']);
});

it('cannot create a product with duplicate name', function (): void {
    Product::factory()->create(['name' => 'Existing Product']);

    postJson('/api/v1/products', ['name' => 'Existing Product'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('can update a product', function (): void {
    $product = Product::factory()->create(['name' => 'Old Name']);

    putJson('/api/v1/products/'.$product->id, ['name' => 'New Name'])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'New Name');

    assertDatabaseHas('products', [
        'id'   => $product->id,
        'name' => 'New Name',
    ]);
});

it('can update a product while keeping same name', function (): void {
    $product = Product::factory()->create(['name' => 'Same Name']);

    putJson('/api/v1/products/'.$product->id, ['name' => 'Same Name'])
        ->assertSuccessful();

    assertDatabaseHas('products', [
        'id'   => $product->id,
        'name' => 'Same Name',
    ]);
});

it('cannot update a product to an existing name of another product', function (): void {
    $product1 = Product::factory()->create(['name' => 'Product 1']);
    $product2 = Product::factory()->create(['name' => 'Product 2']);

    putJson('/api/v1/products/'.$product1->id, ['name' => 'Product 2'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('can delete a product', function (): void {
    $product = Product::factory()->create();

    deleteJson('/api/v1/products/'.$product->id)
        ->assertNoContent();

    assertDatabaseMissing('products', ['id' => $product->id]);
});

it('throws 404 if product not found on delete', function (): void {
    deleteJson('/api/v1/products/999')
        ->assertNotFound();
});
