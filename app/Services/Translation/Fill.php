<?php declare(strict_types=1);

namespace App\Services\Translation;

class Fill extends TranslationAbstract
{
    /**
     * @var array
     */
    protected array $list = [];

    /**
     * @return self
     */
    public function write(): self
    {
        $this->scan();

        return $this->fill(array_map('array_unique', $this->list));
    }

    /**
     * @param string $file
     *
     * @return void
     */
    protected function file(string $file): void
    {
        preg_match_all('/__\([\'"]([^\'"]+)/', file_get_contents($file), $matches);

        foreach ($matches[1] as $string) {
            [$file, $code] = explode('.', $string, 2);
            $this->list[$file][] = $code;
        }
    }

    /**
     * @param array $default
     *
     * @return self
     */
    protected function fill(array $default): self
    {
        foreach (config('app.locales') as $lang) {
            $this->fillLanguage($lang, $default);
        }

        return $this;
    }

    /**
     * @param string $lang
     * @param array $default
     *
     * @return void
     */
    protected function fillLanguage(string $lang, array $default): void
    {
        foreach ($default as $file => $keys) {
            $this->fillLanguageFile($lang, $file, $keys);
        }
    }

    /**
     * @param string $lang
     * @param string $file
     * @param array $keys
     *
     * @return void
     */
    protected function fillLanguageFile(string $lang, string $file, array $keys): void
    {
        $file = base_path('resources/lang/'.$lang.'/'.$file.'.php');
        $values = $this->undot(array_fill_keys($keys, ''));

        if (is_file($file)) {
            $values = array_replace_recursive($values, require $file);
        }

        $this->writeFile($file, $values);
    }

    /**
     * @param string $file
     * @param array $values
     *
     * @return void
     */
    public function writeFile(string $file, array $values): void
    {
        $dir = dirname($file);

        if (is_dir($dir) === false) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($file, '<?php return '.$this->varExport($values).';', LOCK_EX);
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected function varExport(array $array): string
    {
        $export = var_export($array, true);
        $export = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $export);

        $export = preg_split('/\r\n|\n|\r/', $export);
        $export = preg_replace(['/\s*array\s\($/', '/\)(,)?$/', '/\s=>\s$/'], [null, ']$1', ' => ['], $export);

        $export = implode(PHP_EOL, array_filter(['['] + (array)$export));
        $export = preg_replace('(\d+\s=>\s)', '', $export);

        return trim($export);
    }
}
