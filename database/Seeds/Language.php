<?php declare(strict_types=1);

namespace Database\Seeds;

use App\Models;

class Language extends SeederAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $this->language();
    }

    /**
     * @return void
     */
    protected function language()
    {
        $this->truncate('language');

        Models\Language::insert([
            [
                'name' => 'Castellano',
                'iso' => 'es',
                'locale' => 'es_ES',
                'order' => 0,
                'default' => true,
                'enabled' => true
            ],
            [
                'name' => 'English',
                'iso' => 'en',
                'locale' => 'en_US',
                'order' => 1,
                'default' => false,
                'enabled' => true
            ]
        ]);
    }
}
