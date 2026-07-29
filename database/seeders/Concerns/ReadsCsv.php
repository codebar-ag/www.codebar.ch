<?php

declare(strict_types=1);

namespace Database\Seeders\Concerns;

trait ReadsCsv
{
    /**
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $filename): array
    {
        $handle = fopen(database_path("seeders/data/{$filename}"), 'r');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open seeders/data/{$filename}.");
        }

        $header = fgetcsv($handle, 0, ';');

        if ($header === false || in_array(null, $header, true)) {
            fclose($handle);

            throw new \RuntimeException("Missing CSV header in seeders/data/{$filename}.");
        }

        /** @var list<string> $header */
        $rows = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rows[] = array_combine(
                $header,
                array_map(fn (?string $value): string => (string) $value, $row),
            );
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @return array<mixed>
     */
    private function decodeJson(string $value): array
    {
        $decoded = $value === '' ? [] : json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }
}
