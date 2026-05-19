<?php

namespace App\Core\Validator\Types;

use App\Core\Contract\TypeValidatorInterface;

class IntType implements TypeValidatorInterface
{
    public function getName(): string
    {
        return 'int';
    }

    public function isValid(mixed $value): bool
    {
        if ($value === '') return true;

        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}