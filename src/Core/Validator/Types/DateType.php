<?php

namespace App\Core\Validator\Types;

use App\Core\Contract\TypeValidatorInterface;
use DateTime;

class DateType implements TypeValidatorInterface
{
    public function getName(): string
    {
        return 'date';
    }

    public function isValid(mixed $value): bool
    {
        if ($value === '') return true;

        $d = DateTime::createFromFormat('Y-m-d', $value);

        return $d && $d->format('Y-m-d') === $value;
    }
}