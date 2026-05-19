<?php

namespace App\Core\Contract;

interface RuleInterface
{
    public function getName(): string;

    public function validate(string $fieldName, mixed $value, mixed $ruleValue): string|null;
}