<?php declare(strict_types=1);

namespace App\Console\Commands\Translation;

use App\Services\Translation\Fixed as BaseFixed;

class Fixed extends TranslationAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:fixed';

    /**
     * @var string
     */
    protected $description = 'Search fixed texts on app and views';

    /**
     * @return void
     */
    public function handle()
    {
        foreach ((new BaseFixed())->scan() as $status) {
            $this->info($status);
        }
    }
}
