<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Queries\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GetAllProducts extends Query
{
    public function __construct(
        private int $pageSize = 15,
        private int $pageNumber = 1,
        private string $searchTerm = '',
    ) {}

    public function execute(): LengthAwarePaginator
    {
        return DB::table('products')
            ->when($this->searchTerm, function ($query): void {
                $query->where('name', 'like', sprintf('%%%s%%', $this->searchTerm));
            })
            ->paginate($this->pageSize, ['*'], 'page', $this->pageNumber);
    }
}
