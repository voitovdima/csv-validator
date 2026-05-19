<?php

namespace App\Core\Result;

class ValidationReport
{
    private int $processed = 0;
    private int $valid = 0;
    private int $invalid = 0;
    private array $errors = [];

    public function incrementProcessed(): void
    {
        $this->processed++;
    }

    public function incrementValid(): void
    {
        $this->valid++;
    }

    public function incrementInvalid(): void
    {
        $this->invalid++;
    }

    public function addError(int $row, string $field, string $message): void
    {
        $this->errors[] = [
            'row' => $row,
            'field' => $field,
            'message' => $message
        ];
    }

    public function getProcessed(): int
    {
        return $this->processed;
    }

    public function getValid(): int
    {
        return $this->valid;
    }

    public function getInvalid(): int
    {
        return $this->invalid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValidReport(): bool
    {
        return $this->invalid === 0;
    }
}