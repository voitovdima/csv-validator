<?php

namespace App\Core\Validator\Rules;

use App\Core\Contract\RuleInterface;

class RequiredRule implements RuleInterface
{
    public function getName(): string
    {
        return 'required';
    }

    public function validate(string $fieldName, mixed $value, mixed $ruleValue): string|null
    {
        if ($ruleValue === true && ($value === null || $value === '')) {
            return "required field is empty";
        }

        return null;
    }
}