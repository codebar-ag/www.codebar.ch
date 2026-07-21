<?php

namespace Database\Seeders\Concerns;

trait ReadsCsv
{
    /**
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $filename): array
    {
        $handle = fopen(database_path("seeders/data/{$filename}"), 'r');

        $header = fgetcsv($handle, 0, ';');
        $rows = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rows[] = array_combine($header, $row);
        }

        fclose($handle);

        return $rows;
    }

    private function decodeJson(string $value): array
    {
        return $value === '' ? [] : json_decode($value, true);
    }
}
