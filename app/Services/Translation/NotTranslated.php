<?php declare(strict_types=1);

namespace App\Services\Translation;

class NotTranslated extends TranslationAbstract
{
    /**
     * @param string $file
     *
     * @return array
     */
    protected function file(string $file): array
    {
        preg_match_all('/__\([\'"]([^\'"]+)/', file_get_contents($file), $matches);

        $file = str_replace(base_path(), '', $file);
        $empty = [];

        foreach ($matches[1] as $string) {
            if ($string === __($string)) {
                $empty[$file][] = $string;
            }
        }

        return $empty;
    }
}
