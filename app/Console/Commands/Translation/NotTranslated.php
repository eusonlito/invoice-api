<?php declare(strict_types=1);

namespace App\Console\Commands\Translation;

use App\Services\Translation\NotTranslated as BaseNotTranslated;

class NotTranslated extends TranslationAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:not-translated';

    /**
     * @var string
     */
    protected $description = 'Search empty translations on app and views';

    /**
     * @return void
     */
    public function handle()
    {
        foreach ((new BaseNotTranslated())->scan() as $status) {
            $this->info($status);
        }
    }
}
