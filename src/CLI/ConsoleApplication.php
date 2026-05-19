<?php

use App\Core\Reader\CsvStreamReader;
use App\Core\Result\ValidationReport;
use App\Core\Validator\SchemaValidator;

class ConsoleApplication
{
    public function __construct(
        private SchemaValidator $validator,
        private CsvStreamReader $reader,
        private array $formats
    ) {
    }

    public function run(array $argv): int
    {
        if (count($argv) < 3) {
            echo "Usage: php validate_csv.php <format_name> <path_to_csv> [--format=json]\n";

            return 1;
        }

        $formatName = $argv[1];
        $filePath = $argv[2];

        // output in JSON format
        $isJsonOutput = in_array('--format=json', $argv);

        if (!isset($this->formats[$formatName])) {
            echo "Error: Schema format '{$formatName}' not found.\n";

            return 1;
        }

        $schema = $this->formats[$formatName];

        try {
            $header = $this->reader->getHeader($filePath);

            // checking header fields for consistency
            foreach (array_keys($schema) as $expectedField) {
                if (!in_array($expectedField, $header)) {
                    echo "Error: Missing expected header column '{$expectedField}'.\n";

                    return 1;
                }
            }

            $report = new ValidationReport();

            foreach ($this->reader->readRows($filePath) as $rowNumber => $row) {
                $report->incrementProcessed();
                $errors = $this->validator->validateRow($row, $schema);

                if (empty($errors)) {
                    $report->incrementValid();
                } else {
                    $report->incrementInvalid();
                    foreach ($errors as $field => $message) {
                        $report->addError($rowNumber, $field, $message);
                    }
                }
            }

            if ($isJsonOutput) {
                echo json_encode([
                        'format' => $formatName,
                        'file' => $filePath,
                        'processed' => $report->getProcessed(),
                        'valid' => $report->getValid(),
                        'invalid' => $report->getInvalid(),
                        'errors' => $report->getErrors()
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            } else {
                echo "Format: {$formatName}\n";
                echo "File:   {$filePath}\n";
                foreach ($report->getErrors() as $err) {
                    echo "Row {$err['row']}: {$err['field']} - {$err['message']}\n";
                }
                echo "Processed: {$report->getProcessed()}\n";
                echo "Valid:     {$report->getValid()}\n";
                echo "Invalid:   {$report->getInvalid()}\n";
            }

            return $report->isValidReport() ? 0 : 1;

        } catch (Exception $e) {
            echo $e->getMessage() . "\n";

            return 1;
        }
    }
}