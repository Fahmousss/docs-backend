<?php

declare(strict_types=1);

namespace App\Queries\Blog;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetProductBlog extends Query
{
    public function __construct(
        private readonly string $productId,
    ) {}

    /**
     * @return Collection<int, object>
     */
    public function execute(): Collection
    {
        return DB::table('product_blog_view')
            ->where('product_id', $this->productId)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
