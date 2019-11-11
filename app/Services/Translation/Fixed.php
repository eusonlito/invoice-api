<?php declare(strict_types=1);

namespace App\Services\Translation;

class Fixed extends TranslationAbstract
{
    /**
     * @param string $file
     *
     * @return ?array
     */
    protected function file(string $file): ?array
    {
        $contents = file($file);
        $matches = preg_grep('/[^-]>\w[\w\s]+</', $contents) + preg_grep('/(title|placeholder)="[\w\s]+"/', $contents);

        return $matches ? [str_replace(base_path(), '', $file) => $matches] : null;
    }
}
