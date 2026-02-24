<?php

declare(strict_types=1);

namespace App\Exceptions;

final class ModelNotFoundException extends \Illuminate\Database\Eloquent\ModelNotFoundException
{
    public function setModel($model, $ids = [], $message = ''): self
    {
        $this->model = $model;
        $this->ids = $ids;
        $this->message = $message;

        return $this;
    }
}
