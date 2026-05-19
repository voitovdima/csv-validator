<?php

use App\Core\Reader\CsvStreamReader;
use App\Core\Validator\Rules\MaxRule;
use App\Core\Validator\Rules\RequiredRule;
use App\Core\Validator\SchemaValidator;
use App\Core\Validator\Types\DateType;
use App\Core\Validator\Types\EmailType;
use App\Core\Validator\Types\IntType;
use App\Core\Validator\Types\StringType;

require_once __DIR__ . '/vendor/autoload.php';

$validator = new SchemaValidator();
$validator->registerType(new IntType());
$validator->registerType(new StringType());
$validator->registerType(new EmailType());
$validator->registerType(new DateType());

$validator->registerRule(new RequiredRule());
$validator->registerRule(new MaxRule());

$reader = new CsvStreamReader();
$formats = require __DIR__ . '/config/formats.php';

$app = new ConsoleApplication($validator, $reader, $formats);

// run
$exitCode = $app->run($argv);
exit($exitCode);