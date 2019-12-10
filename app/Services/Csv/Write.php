<?php declare(strict_types=1);

namespace App\Services\Csv;

class Write
{
    /**
     * @param array $data
     *
     * @return string
     */
    public static function fromArray(array $data): string
    {
        $file = fopen('php://temp', 'rw');

        fputcsv($file, array_keys(current($data)));

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        rewind($file);

        $csv = stream_get_contents($file);

        fclose($file);

        return $csv;
    }
}
