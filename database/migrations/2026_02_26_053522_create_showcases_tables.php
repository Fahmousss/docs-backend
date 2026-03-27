<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('showcase_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('media_url')->nullable();
            $table->date('publish_date')->nullable();
            $table->text('content')->nullable();
            $table->integer('sort_order')->index();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement(<<<'SQL'
                CREATE MATERIALIZED VIEW IF NOT EXISTS product_showcase_view AS
                SELECT
                    id::uuid AS item_id,
                    product_id::uuid AS product_id,
                    title,
                    description,
                    media_url,
                    publish_date,
                    content,
                    sort_order,
                    created_at,
                    updated_at
                FROM showcase_items;
            SQL);

            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_showcase_product ON product_showcase_view (product_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_showcase_sort_order ON product_showcase_view (sort_order)');
        } else {
            DB::statement(<<<'SQL'
                CREATE VIEW IF NOT EXISTS product_showcase_view AS
                SELECT
                    id AS item_id,
                    product_id AS product_id,
                    title,
                    description,
                    media_url,
                    publish_date,
                    content,
                    sort_order,
                    created_at,
                    updated_at
                FROM showcase_items;
            SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS product_showcase_view');
        } else {
            DB::statement('DROP VIEW IF EXISTS product_showcase_view');
        }

        Schema::dropIfExists('showcase_items');
    }
};
