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
                CREATE MATERIALIZED VIEW IF NOT EXISTS product_docs_view AS
                SELECT
                    s.product_id::uuid AS product_id,
                    s.id::uuid AS section_id,
                    s.section_name,
                    s.sort_order AS section_sort,
                    m.id::uuid AS menu_id,
                    m.menu_name,
                    m.sort_order AS menu_sort,
                    sm.id::uuid AS submenu_id,
                    sm.submenu_name,
                    sm.content,
                    sm.sort_order AS submenu_sort
                FROM sections s
                LEFT JOIN menus m ON m.section_id = s.id
                LEFT JOIN submenus sm ON sm.menu_id = m.id;
            SQL);

            // Helpful indexes for fast filtering by product and sorting (supported on materialized views)
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_docs_product ON product_docs_view (product_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_docs_section_sort ON product_docs_view (section_sort)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_docs_menu_sort ON product_docs_view (menu_sort)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_product_docs_submenu_sort ON product_docs_view (submenu_sort)');
        } else {
            // SQLite (tests) fallback: create a normal VIEW without casts
            DB::statement(<<<'SQL'
                CREATE VIEW IF NOT EXISTS product_docs_view AS
                SELECT
                    s.product_id AS product_id,
                    s.id AS section_id,
                    s.section_name,
                    s.sort_order AS section_sort,
                    m.id AS menu_id,
                    m.menu_name,
                    m.sort_order AS menu_sort,
                    sm.id AS submenu_id,
                    sm.submenu_name,
                    sm.content,
                    sm.sort_order AS submenu_sort
                FROM sections s
                LEFT JOIN menus m ON m.section_id = s.id
                LEFT JOIN submenus sm ON sm.menu_id = m.id;
            SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP MATERIALIZED VIEW IF EXISTS product_docs_view');
        } else {
            DB::statement('DROP VIEW IF EXISTS product_docs_view');
        }
    }
};
