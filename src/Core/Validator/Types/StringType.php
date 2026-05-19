<?php

namespace App\Core\Validator\Types;

use App\Core\Contract\TypeValidatorInterface;

class StringType implements TypeValidatorInterface
{
    public function getName(): string
    {
        return 'string';
    }

    public function isValid(mixed $value): bool
    {
        return is_string($value);
    }
}