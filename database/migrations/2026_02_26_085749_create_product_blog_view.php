<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement(<<<'SQL'
                CREATE MATERIALIZED VIEW IF NOT EXISTS product_blog_view AS
                SELECT
                    bs.id::uuid AS section_id,
                    bs.product_id::uuid AS product_id,
                    bs.title,
                    bs.publish_date,
                    bs.description,
                    bs.content,
                    bs.hero_image_url,
                    bs.creators,
                    bs.sort_order
                FROM blog_sections bs;
            SQL);

            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_blog_product ON product_blog_view (product_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_blog_sorts ON product_blog_view (sort_order)');
        } else {
            DB::statement(<<<'SQL'
                CREATE VIEW IF NOT EXISTS product_blog_view AS
                SELECT
                    bs.id AS section_id,
                    bs.product_id AS product_id,
                    bs.title,
                    bs.publish_date,
                    bs.description,
                    bs.content,
                    bs.hero_image_url,
                    bs.creators,
                    bs.sort_order
                FROM blog_sections bs;
            SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS product_blog_view');
        } else {
            DB::statement('DROP VIEW IF EXISTS product_blog_view');
        }
    }
};
