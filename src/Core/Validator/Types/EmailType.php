<?php

namespace App\Core\Validator\Types;

use App\Core\Contract\TypeValidatorInterface;

class EmailType implements TypeValidatorInterface
{
    public function getName(): string
    {
        return 'email';
    }

    public function isValid(mixed $value): bool
    {
        if ($value === '') return true;

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}