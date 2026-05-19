<?php

namespace App\Core\Contract;

interface TypeValidatorInterface
{
    public function getName(): string;
    public function isValid(mixed $value): bool;
}