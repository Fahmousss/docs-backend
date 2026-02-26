<?php

declare(strict_types=1);

use Dedoc\Scramble\Scramble;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API routes are versioned using grazulex/laravel-apiroute v2.x.
| Versions are defined in config/apiroute.php and route files are
| located in routes/api/{version}.php
|
| Supports URI path, header, query, and Accept header detection.
| See config/apiroute.php for configuration options.
|
*/

// Routes are now loaded automatically from config/apiroute.php
// See routes/api/v1.php for version 1 routes

Scramble::registerUiRoute('docs/v1', api: 'v1');
Scramble::registerJsonSpecificationRoute('docs/v1/api.json', api: 'v1');

/*
 * To add a new API version (e.g., v2):
 *
 * 1. Add the version to the 'versions' array in config/apiroute.php
 * 2. Create the corresponding route file in routes/api/v2.php
 * 3. Register the documentation routes:
 *    Scramble::registerUiRoute('docs/v2', api: 'v2');
 *    Scramble::registerJsonSpecificationRoute('docs/v2/api.json', api: 'v2');
 */
