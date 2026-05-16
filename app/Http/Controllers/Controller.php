<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;

abstract class Controller
{
    protected function isForeignKeyConstraintViolation(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'foreign key constraint failed')
            || str_contains($message, 'foreign key constraint fails')
            || str_contains($message, 'cannot delete or update a parent row')
            || $exception->getCode() === '23503';
    }
}
