<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.16
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- spatie/laravel-data (DATA) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `pest-testing` — Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, browser testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture (CQRS + Pipelines)

- Strict Directory Rules: Stick to the established CQRS directory structure. Do not create new base folders in the `app/` directory without explicit approval.
- `app/Commands/`: Use this exclusively for "Write" operations. These must be single-responsibility classes executed inside a Pipeline.
- `app/Queries/`: Use this exclusively for "Read" operations. These must bypass Eloquent and use the Query Builder to return raw data.
- `app/Processes/`: Use this to define the Pipeline arrays that chain multiple Commands together to complete a specific business goal.
- `app/DTOs/`: Use this for strongly typed data objects (via `spatie/laravel-data`) that carry data from the Request through the Pipeline.
- `app/Services/`: Reserve this folder entirely for external integrations (like third-party APIs or infrastructure tools). Do not put core business logic here.
- Domain Grouping: Always group files by their specific domain inside the base folders (e.g., `app/Commands/Documentation/` or `app/Queries/Users/`).
- Dependency Management: Do not change or add to the application's core dependencies without approval.

## CQRS + Pipeline (Advanced) Architecture Guideline

- Responsibility Segregation: Commands (Writes) and Queries (Reads) are handled by entirely separate logic flows to optimize for their specific requirements.
- Command Flow (Writes): All data mutations must pass through a Pipeline of single-responsibility tasks using Eloquent ORM to ensure data integrity, trigger model events, and handle complex business rules.
- Query Flow (Reads): Data retrieval bypasses the Pipeline and the ORM, utilizing the Laravel DB Query Builder or PostgreSQL Materialized Views to minimize memory overhead and maximize speed.
- Immutable DTOs: All data entering the system must be mapped into strictly typed `spatie/laravel-data` objects, serving as a contract between the HTTP layer and the business logic.
- Single-Task Actions: Each step in a pipeline is an isolated class with one job (e.g., sanitizing HTML, syncing a specific table, or refreshing a view).
- Thin Controllers: Controllers are strictly traffic controllers; they resolve DTOs and trigger Processes, containing zero business or database logic.

### The Process Abstract

All multi-step write operations must extend this abstract class to utilize Laravel's internal Pipeline feature.

```php
namespace App\Processes;

use Illuminate\Support\Facades\Pipeline;

/**
 * Abstract Process
 * * Provides the mechanism to send a DTO through a series of
 * sequential, isolated Command tasks.
 */
abstract class Process
{
    /**
     * The array of class-strings representing the tasks to be executed.
     * * @var array<int, class-string>
     */
    protected array $tasks = [];

    /**
     * Execute the pipeline with the provided payload.
     *
     * @param object $payload Usually a spatie/laravel-data DTO
     * @return mixed
     */
    public function run(object $payload): mixed
    {
        return Pipeline::send(passable: $payload)
            ->through(pipes: $this->tasks)
            ->thenReturn();
    }
}

```

### 🛠️ Execution Flow Example

1. **Request:** User submits a documentation update containing nested sections, menus, and content.
2. **DTO:** The `StoreDocumentationRequest` maps the input into a `DocumentationPayload` DTO in `after()` validation.
3. **Process:** The `UpdateDocumentationProcess` is triggered in the Controller.
4. **Pipeline Tasks:**
- `SanitizeContent`: Cleans rich text strings.
- `SyncDocumentation`: Uses Eloquent `updateOrCreate` to manage the hierarchy.
- `RefreshView`: Updates the Materialized View for read optimization.
5. **Read:** When a user views the site, the `GetProductDocs` Query fetches from the pre-joined Materialized View via Query Builder.

### Implementation Example (Command Task)

Commands are executed inside a Pipeline. They receive a validated DTO and use Eloquent to safely update the database.

```php
namespace App\Commands\Orders;

use Closure;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\DTOs\Orders\CheckoutData;

final class ProcessOrderPayment
{
    public function handle(CheckoutData $payload, Closure $next): mixed
    {
        // We use Database Transactions to ensure complete rollback on failure
        DB::transaction(function () use ($payload) {
            
            // 🟢 ELOQUENT ORM is used here to safely create the record
            $order = Order::create([
                'user_id' => $payload->userId,
                'total_amount' => $payload->cartTotal,
                'status' => 'paid',
            ]);

            // Eloquent relationship handles the child records safely
            foreach ($payload->items as $item) {
                $order->items()->create([
                    'product_id' => $item->id,
                    'price' => $item->price,
                ]);
            }

            $payload->orderId = $order->id;
        });

        return $next($payload);
    }
}
```

### Implementation Example (Queries)

Queries bypass Eloquent and use the Query Builder directly against the Materialized View.

```php
namespace App\Queries\Documentation;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

final class GetProductDocumentation
{
public function execute(int $productId): Collection
{
// Lightning-fast read using Query Builder directly against the View
return DB::table('product_docs_view')
->where('product_id', $productId)
->orderBy('section_sort')
->orderBy('menu_sort')
->orderBy('submenu_sort')
->get();

        // You can then map this flat collection back into a nested array/JSON 
        // structure for your frontend UI to render the sidebar and content.
    }
}

```

## Frontend Bundling

- There is no need to modify frontend assets.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Group Form Requests by feature area.
- Check sibling Form Requests to see if the application uses array or string based validation rules.
- Use `validate()` instead of `validateOrFail()` in controllers.
- Use `withErrors()` instead of `withValidationErrors()` in controllers.
- Use ApiResponse Trait to return JSON responses in controllers.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

</laravel-boost-guidelines>
