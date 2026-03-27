<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Blog\BlogController;
use App\Http\Controllers\Api\V1\Blog\Section\SectionController as BlogSectionController;
use App\Http\Controllers\Api\V1\Documentation\DocumentationController;
use App\Http\Controllers\Api\V1\Documentation\Menu\MenuController as DocumentationMenuController;
use App\Http\Controllers\Api\V1\Documentation\NavigationController;
use App\Http\Controllers\Api\V1\Documentation\Section\SectionController as DocumentationSectionController;
use App\Http\Controllers\Api\V1\Documentation\Submenu\SubmenuController as DocumentationSubmenuController;
use App\Http\Controllers\Api\V1\Preferences\Item\ItemController as PreferencesItemController;
use App\Http\Controllers\Api\V1\Preferences\PreferencesController;
use App\Http\Controllers\Api\V1\Preferences\Section\SectionController as PreferencesSectionController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Product\ProductSelectController;
use App\Http\Controllers\Api\V1\Showcase\Item\ItemController as ShowcaseItemController;
use App\Http\Controllers\Api\V1\Showcase\ShowcaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/

// -------------------------------------------------------------------------
// Auth
// -------------------------------------------------------------------------
Route::middleware(['throttle:auth'])->group(function (): void {
    Route::post('login', LoginController::class)->name('api.v1.login');
});

// -------------------------------------------------------------------------
// Public Routes
// -------------------------------------------------------------------------

// ✅ Static routes HARUS di atas apiResource agar tidak tertangkap {product}
Route::prefix('products')->name('api.v1.products.')->group(function (): void {
    Route::get('select', ProductSelectController::class)
        ->name('select');

    Route::get('docs', [DocumentationController::class, 'index'])
        ->name('documentation.index');

    Route::get('{productId}/docs', [DocumentationController::class, 'show'])
        ->name('docs.show');

    Route::get('{productId}/showcase', [ShowcaseController::class, 'show'])
        ->name('showcase.show');

    Route::get('{productId}/preferences', [PreferencesController::class, 'show'])
        ->name('preferences.show');

    Route::get('{productId}/blog', [BlogController::class, 'show'])
        ->name('blog.show');

    Route::get('{productId}/docs/navigation', NavigationController::class)
        ->name('docs.navigation');

    Route::get('{productId}/docs/menus/{menuId}/submenus', App\Http\Controllers\Api\V1\Documentation\Menu\MenuSubmenuController::class)
        ->name('docs.menus.submenus');
});

// ✅ apiResource di bawah static routes + constraint UUID agar aman
Route::apiResource('products', ProductController::class)
    ->whereUuid('product')
    ->names('api.v1.products');

// -------------------------------------------------------------------------
// Protected Routes
// -------------------------------------------------------------------------
Route::middleware(['log.api'])->group(function (): void {
    Route::post('logout', LogoutController::class)->name('api.v1.logout');
    Route::get('me', MeController::class)->name('api.v1.me');

    Route::prefix('products/{productId}')->name('api.v1.products.')->group(function (): void {

        // Documentation
        Route::prefix('docs')->name('docs.')->group(function (): void {
            Route::put('/', [DocumentationController::class, 'update'])->name('docs.update');

            Route::apiResource('sections', DocumentationSectionController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.docs.sections');

            Route::apiResource('sections.menus', DocumentationMenuController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.docs.sections.menus');

            Route::apiResource('sections.menus.submenus', DocumentationSubmenuController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.docs.sections.menus.submenus');
        });

        // Showcase
        Route::prefix('showcase')->name('showcase.')->group(function (): void {
            Route::put('/', [ShowcaseController::class, 'update'])->name('update');

            Route::apiResource('items', ShowcaseItemController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.showcase.items');
        });

        // Preferences
        Route::prefix('preferences')->name('preferences.')->group(function (): void {
            Route::put('/', [PreferencesController::class, 'update'])->name('update');

            Route::apiResource('sections', PreferencesSectionController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.preferences.sections');

            Route::apiResource('sections.items', PreferencesItemController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.preferences.sections.items');
        });

        // Blog
        Route::prefix('blog')->name('blog.')->group(function (): void {
            Route::put('/', [BlogController::class, 'update'])->name('update');

            Route::apiResource('sections', BlogSectionController::class)
                ->only(['store', 'update', 'destroy'])
                ->names('api.v1.products.blog.sections');
        });
    });
});
