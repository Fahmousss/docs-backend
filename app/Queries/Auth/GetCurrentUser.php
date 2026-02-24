<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Queries\Query;
use Illuminate\Http\Request;

final class GetCurrentUser extends Query
{
    public function __construct(private readonly Request $request) {}

    public function get(): mixed
    {
        return $this->request->user();
    }
}
