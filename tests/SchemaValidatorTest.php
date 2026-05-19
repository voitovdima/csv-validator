<?php

namespace tests;

use App\Core\Validator\Rules\MaxRule;
use App\Core\Validator\Rules\RequiredRule;
use App\Core\Validator\SchemaValidator;
use App\Core\Validator\Types\DateType;
use App\Core\Validator\Types\EmailType;
use App\Core\Validator\Types\IntType;
use App\Core\Validator\Types\StringType;
use PHPUnit\Framework\TestCase;

class SchemaValidatorTest extends TestCase
{
    private SchemaValidator $validator;
    private array $schema;

    protected function setUp(): void
    {
        $this->validator = new SchemaValidator();
        $this->validator->registerType(new IntType());
        $this->validator->registerType(new StringType());
        $this->validator->registerType(new EmailType());
        $this->validator->registerType(new DateType());
        $this->validator->registerRule(new RequiredRule());
        $this->validator->registerRule(new MaxRule());

        $this->schema = [
            'customer_id' => ['type' => 'int', 'required' => true],
            'registered_at' => ['type' => 'date', 'required' => true],
            'email' => ['type' => 'email', 'required' => false],
            'username' => ['type' => 'string', 'max' => 10, 'required' => false],
        ];
    }

    public function testValidRowPasses(): void
    {
        $row = [
            'customer_id' => '125',
            'registered_at' => '2026-05-19',
            'email' => 'dev@example.com',
            'username' => 'php_dev'
        ];

        $errors = $this->validator->validateRow($row, $this->schema);

        $this->assertEmpty($errors);
    }

    public function testMissingRequiredFieldReturnsError(): void
    {
        $row = [
            'customer_id' => '',
            'registered_at' => '2026-05-19',
            'email' => 'dev@example.com',
            'username' => 'test'
        ];

        $errors = $this->validator->validateRow($row, $this->schema);

        $this->assertArrayHasKey('customer_id', $errors);
        $this->assertEquals('required field is empty', $errors['customer_id']);
    }

    public function testInvalidEmailReturnsError(): void
    {
        $row = [
            'customer_id' => '1',
            'registered_at' => '2026-05-19',
            'email' => 'not-an-email',
            'username' => 'test'
        ];

        $errors = $this->validator->validateRow($row, $this->schema);

        $this->assertArrayHasKey('email', $errors);
        $this->assertStringContainsString('is not a valid email', $errors['email']);
    }

    public function testStringMaxLengthExceeded(): void
    {
        $row = [
            'customer_id' => '1',
            'registered_at' => '2026-05-19',
            'email' => 'dev@example.com',
            'username' => 'very_long_username_exceeding_ten'
        ];

        $errors = $this->validator->validateRow($row, $this->schema);
        
        $this->assertArrayHasKey('username', $errors);
        $this->assertStringContainsString('value exceeds max length', $errors['username']);
    }
}