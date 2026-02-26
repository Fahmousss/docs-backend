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
        Schema::create('preference_sections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('sort_order')->index();
            $table->timestamps();
        });

        Schema::create('preference_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('preference_section_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->text('content')->nullable();
            $table->integer('sort_order')->index();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement(<<<'SQL'
                CREATE MATERIALIZED VIEW IF NOT EXISTS product_preferences_view AS
                SELECT
                    pi.id::uuid AS item_id,
                    ps.product_id::uuid AS product_id,
                    ps.id::uuid AS section_id,
                    ps.name AS section_name,
                    ps.sort_order AS section_sort,
                    pi.item_name,
                    pi.content,
                    pi.sort_order AS item_sort
                FROM preference_items pi
                JOIN preference_sections ps ON ps.id = pi.preference_section_id;
            SQL);

            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_preferences_product ON product_preferences_view (product_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_preferences_sorts ON product_preferences_view (section_sort, item_sort)');
        } else {
            DB::statement(<<<'SQL'
                CREATE VIEW IF NOT EXISTS product_preferences_view AS
                SELECT
                    pi.id AS item_id,
                    ps.product_id AS product_id,
                    ps.id AS section_id,
                    ps.name AS section_name,
                    ps.sort_order AS section_sort,
                    pi.item_name,
                    pi.content,
                    pi.sort_order AS item_sort
                FROM preference_items pi
                JOIN preference_sections ps ON ps.id = pi.preference_section_id;
            SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS product_preferences_view');
        } else {
            DB::statement('DROP VIEW IF EXISTS product_preferences_view');
        }

        Schema::dropIfExists('preference_items');
        Schema::dropIfExists('preference_sections');
    }
};
