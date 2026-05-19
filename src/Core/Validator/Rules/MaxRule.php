<?php

namespace App\Core\Validator\Rules;

use App\Core\Contract\RuleInterface;

class MaxRule implements RuleInterface
{
    public function getName(): string
    {
        return 'max';
    }

    public function validate(string $fieldName, mixed $value, mixed $ruleValue): string|null
    {
        if ($value !== null && mb_strlen((string)$value) > (int)$ruleValue) {
            return "value exceeds max length of {$ruleValue} characters";
        }

        return null;
    }
}