<?php

declare(strict_types=1);

namespace App\Queries\Documentation;

use App\Queries\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GetAllProductDocumentation extends Query
{
    public function __construct(
        private int $pageSize = 15,
        private int $pageNumber = 1,
        private string $searchTerm = '',
    ) {}

    public function execute(): LengthAwarePaginator
    {
        return DB::table('products as p')
            ->leftJoin('sections as s', 's.product_id', '=', 'p.id')
            ->leftJoin('preference_sections as ps', 'ps.product_id', '=', 'p.id')
            ->leftJoin('preference_items as pi', 'pi.preference_section_id', '=', 'ps.id')
            ->leftJoin('blog_sections as bs', 'bs.product_id', '=', 'p.id')
            ->leftJoin('showcase_items as si', 'si.product_id', '=', 'p.id')
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                DB::raw("STRING_AGG(DISTINCT s.section_name, ', ' ORDER BY s.section_name) as sections"),
                DB::raw("STRING_AGG(DISTINCT ps.name, ', ' ORDER BY ps.name) as preference_sections"),
                DB::raw("STRING_AGG(DISTINCT pi.item_name, ', ' ORDER BY pi.item_name) as preference_items"),
                DB::raw("STRING_AGG(DISTINCT bs.title, ', ' ORDER BY bs.title) as blogs"),
                DB::raw("STRING_AGG(DISTINCT si.title, ', ' ORDER BY si.title) as showcases"),
            ])
            ->when(
                $this->searchTerm !== '',
                fn ($query) => $query->where('p.name', 'ilike', sprintf('%%%s%%', $this->searchTerm))
            )
            ->groupBy('p.id', 'p.name')
            // Menambahkan filter agar minimal salah satu kolom tidak NULL
            ->havingRaw('
            COUNT(s.id) > 0 OR 
            COUNT(ps.id) > 0 OR 
            COUNT(bs.id) > 0 OR 
            COUNT(si.id) > 0
        ')
            ->orderBy('p.id')
            ->paginate(
                perPage: $this->pageSize,
                page: $this->pageNumber,
            );
    }
}
