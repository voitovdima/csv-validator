<?php

namespace App\Core\Reader;

use InvalidArgumentException;
use RuntimeException;

class CsvStreamReader
{
    public function readRows(string $filePath, string $delimiter = ','): \Generator
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("Error: File '{$filePath}' missing or unreadable.");
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Error: Cannot open file '{$filePath}'.");
        }

        try {
            $header = fgetcsv($handle, 0, $delimiter);
            if ($header === false) {
                throw new RuntimeException("Error: CSV file is empty.");
            }

            $header = array_map('trim', $header);
            $rowNumber = 2; // row 1 — header

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                // if row empty - skip
                if (count($data) === 1 && $data[0] === null) {
                    continue;
                }

                // combin header keys with row values
                $paddedData = array_pad($data, count($header), '');
                $slicedData = array_slice($paddedData, 0, count($header));
                $row = array_combine($header, $slicedData);

                yield $rowNumber => $row;
                $rowNumber++;
            }
        } finally {
            fclose($handle);
        }
    }

    public function getHeader(string $filePath, string $delimiter = ','): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("Error: File '{$filePath}' missing or unreadable.");
        }

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle, 0, $delimiter);
        fclose($handle);

        return $header ? array_map('trim', $header) : [];
    }
}