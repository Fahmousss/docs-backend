<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation\Menu;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Documentation\SubmenuResource;
use App\Queries\Documentation\GetMenuSubmenus;
use Illuminate\Http\JsonResponse;

final class MenuSubmenuController extends ApiController
{
    /**
     * Get submenus by menu
     *
     * @unauthenticated
     *
     * @response array{ success: bool, message: string, data: array{ items: SubmenuResource[] } }
     */
    public function __invoke(string $productId, string $menuId): JsonResponse
    {
        $submenus = (new GetMenuSubmenus($productId, $menuId))->execute();

        return $this->success(
            SubmenuResource::collection($submenus),
            'Submenus retrieved successfully'
        );
    }
}
