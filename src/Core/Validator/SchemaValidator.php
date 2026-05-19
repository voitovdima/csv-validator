<?php

namespace App\Core\Validator;

use App\Core\Contract\RuleInterface;
use App\Core\Contract\TypeValidatorInterface;

class SchemaValidator
{
    private array $types = [];

    private array $rules = [];

    public function registerType(TypeValidatorInterface $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    public function registerRule(RuleInterface $rule): void
    {
        $this->rules[$rule->getName()] = $rule;
    }

    public function validateRow(array $row, array $schema): array
    {
        $errors = [];

        foreach ($schema as $fieldName => $fieldRules) {
            $value = $row[$fieldName] ?? null;

            if (isset($this->rules['required']) && isset($fieldRules['required'])) {
                $reqError = $this->rules['required']->validate($fieldName, $value, $fieldRules['required']);
                if ($reqError) {
                    $errors[$fieldName] = $reqError;
                    continue;
                }
            }

            if ($value === null || $value === '') {
                continue;
            }

            $typeName = $fieldRules['type'] ?? null;
            if ($typeName && isset($this->types[$typeName])) {
                if (!$this->types[$typeName]->isValid($value)) {
                    if ($typeName === 'email') {
                        $errors[$fieldName] = "\"{$value}\" is not a valid email";
                    } elseif ($typeName === 'date') {
                        $errors[$fieldName] = "\"{$value}\" is not a valid date";
                    } else {
                        $errors[$fieldName] = "\"{$value}\" is not a valid {$typeName}";
                    }
                    continue;
                }
            }
            
            foreach ($fieldRules as $ruleName => $ruleValue) {
                if (in_array($ruleName, ['type', 'required'])) {
                    continue;
                }

                if (isset($this->rules[$ruleName])) {
                    $ruleError = $this->rules[$ruleName]->validate($fieldName, $value, $ruleValue);
                    if ($ruleError) {
                        $errors[$fieldName] = $ruleError;
                    }
                }
            }
        }

        return $errors;
    }
}