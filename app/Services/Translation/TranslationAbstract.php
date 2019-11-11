<?php declare(strict_types=1);

namespace App\Services\Translation;

use Generator;
use Illuminate\Support\Arr;

abstract class TranslationAbstract
{
    /**
     * @var array
     */
    protected array $folders = ['app', 'resources/views'];

    /**
     * @param array $folders = []
     *
     * @return self
     */
    public function __construct(array $folders = [])
    {
        if ($folders) {
            $this->folders = $folders;
        }
    }

    /**
     * @return array
     */
    public function scan(): array
    {
        $found = [];

        foreach ($this->folders as $folder) {
            foreach ($this->read(base_path($folder)) as $file) {
                if ($matches = $this->file($file)) {
                    $found[str_replace(base_path(), '', $file)] = $matches;
                }
            }
        }

        return $found;
    }

    /**
     * @param string $dir
     *
     * @return \Generator
     */
    protected function read(string $dir): Generator
    {
        if (!is_dir($dir)) {
            return [];
        }

        $dh = opendir($dir);

        while (($file = readdir($dh)) !== false) {
            if (($file === '.') || ($file === '..')) {
                continue;
            }

            $file = $dir.'/'.$file;

            if (is_dir($file)) {
                yield from $this->read($file);
            }

            if (is_file($file) && preg_match('/\.php$/', $file)) {
                yield $file;
            }
        }

        closedir($dh);

        return [];
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function undot(array $array): array
    {
        ksort($array);

        $values = [];

        foreach ($array as $key => $value) {
            Arr::set($values, $key, $value);
        }

        return $values;
    }
}
