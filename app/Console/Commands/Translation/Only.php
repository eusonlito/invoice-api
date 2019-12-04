<?php declare(strict_types=1);

namespace App\Console\Commands\Translation;

use App\Services\Translation\Only as BaseOnly;

class Only extends TranslationAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:only {lang}';

    /**
     * @var string
     */
    protected $description = 'Search translations only in one language';

    /**
     * @return void
     */
    public function handle()
    {
        foreach ((new BaseOnly((string)$this->argument('lang')))->scan() as $status) {
            $this->info($status);
        }
    }
}
