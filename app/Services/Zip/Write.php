<?php declare(strict_types=1);

namespace App\Services\Zip;

use ZipArchive;

class Write
{
    /**
     * @param array $files
     * @param bool $cached
     *
     * @return string
     */
    public static function fromArray(array $files, bool $cached): string
    {
        $file = static::file($files);

        if ($cached && is_file($file)) {
            return file_get_contents($file);
        }

        static::create($file, $files);

        return file_get_contents($file);
    }

    /**
     * @param array $files
     *
     * @return string
     */
    protected static function file(array $files): string
    {
        return service()->disk()->path('tmp/'.md5(implode($files)).'.zip');
    }

    /**
     * @param string $file
     * @param array $files
     *
     * @return void
     */
    protected static function create(string $file, array $files): void
    {
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::CREATE);

        foreach ($files as $name => $each) {
            $zip->addFile($each, $name);
        }

        $zip->close();
    }
}
