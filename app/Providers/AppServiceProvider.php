<?php

declare(strict_types=1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureScrambleAuthentication();
        $this->configureScrambleApi();
    }

    /**
     * Configure the rate limiters for the application.
     */
    private function configureRateLimiting(): void
    {
        // Default API rate limiter - 60 requests per minute
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        // Auth endpoints - more restrictive (prevent brute force)
        RateLimiter::for('auth', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        // Authenticated user requests - higher limit
        RateLimiter::for('authenticated', fn (Request $request) => $request->user()
            ? Limit::perMinute(120)->by($request->user()->id)
            : Limit::perMinute(60)->by($request->ip()));
    }

    /**
     * Configure Scramble authentication
     * */
    private function configureScrambleAuthentication(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi): void {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }

    private function configureScrambleApi(): void
    {
        Scramble::configure()
            ->expose(false);

        Scramble::registerApi('v1', [
            'api_path' => 'api/v1',
            'info'     => [
                'version'     => '1.0.0',
                'description' => 'API v1 description for Scramble',
            ],
            'ui' => [
                'title'        => 'API v1',
                'theme'        => 'system',
                'hide_try_it'  => false,
                'hide_schemas' => true,
            ],
        ]);

        /*
         * To add a new API version, register it using Scramble::registerApi.
         *
         * Example for v2:
         * Scramble::registerApi('v2', [
         *     'api_path' => 'api/v2',
         *     'info'     => [
         *         'version'     => '2.0.0',
         *         'description' => 'API v2 description for Scramble',
         *     ],
         *     'ui' => [
         *         'title'        => 'API v2',
         *         'theme'        => 'system',
         *         'hide_schemas' => true,
         *     ],
         * ]);
         */
    }
}
