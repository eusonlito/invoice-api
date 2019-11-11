<?php declare(strict_types=1);

namespace App\Console\Commands\Translation;

use App\Services\Translation\Fill as BaseFill;

class Fill extends TranslationAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:fill';

    /**
     * @var string
     */
    protected $description = 'Fill PHP files with translation codes';

    /**
     * @return void
     */
    public function handle()
    {
        (new BaseFill())->write();
    }
}
