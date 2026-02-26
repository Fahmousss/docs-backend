<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Blog\BlogController;
use App\Http\Controllers\Api\V1\Documentation\DocumentationController;
use App\Http\Controllers\Api\V1\Preferences\PreferencesController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Showcase\ShowcaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Routes for API version 1.
|
*/

// Public routes with auth rate limiter (5/min - brute force protection)
Route::middleware('throttle:auth')->group(function (): void {
    Route::post('login', LoginController::class)->name('api.v1.login');
});

// Public routes without authentication
Route::get('products/{productId}/docs', [DocumentationController::class, 'show'])->name('api.v1.products.docs.show');
Route::get('products/{productId}/showcase', [ShowcaseController::class, 'show'])->name('api.v1.products.showcase.show');
Route::get('products/{productId}/preferences', [PreferencesController::class, 'show'])->name('api.v1.products.preferences.show');
Route::get('products/{productId}/blog', [BlogController::class, 'show'])->name('api.v1.products.blog.show');

// Protected routes with authenticated rate limiter (120/min)
Route::middleware(['auth:sanctum', 'throttle:authenticated', 'log.api'])->group(function (): void {
    Route::post('logout', LogoutController::class)->name('api.v1.logout');
    Route::get('me', MeController::class)->name('api.v1.me');

    // Products
    Route::apiResource('products', ProductController::class)->names('api.v1.products');

    // Documentation
    Route::prefix('products/{productId}/docs')->group(function (): void {
        Route::put('/', [DocumentationController::class, 'update'])->name('api.v1.products.docs.update');
    });

    // Showcase
    Route::prefix('products/{productId}/showcase')->group(function (): void {
        Route::put('/', [ShowcaseController::class, 'update'])->name('api.v1.products.showcase.update');
    });

    // Preferences
    Route::prefix('products/{productId}/preferences')->group(function (): void {
        Route::put('/', [PreferencesController::class, 'update'])->name('api.v1.products.preferences.update');
    });

    // Blog
    Route::prefix('products/{productId}/blog')->group(function (): void {
        Route::put('/', [BlogController::class, 'update'])->name('api.v1.products.blog.update');
    });
});
