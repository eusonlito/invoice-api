<?php declare(strict_types=1);

namespace Database\Seeds;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models;

class Country extends SeederAbstract
{
    /**
     * @var array
     */
    protected array $list = [];

    /**
     * @return void
     */
    public function run()
    {
        $this->insert();
        $this->update();
    }

    /**
     * @return void
     */
    protected function insert()
    {
        $this->truncate('country');

        foreach ($this->json('country-state') as $iso => $row) {
            $this->country($iso, $row);
        }
    }

    /**
     * @param string $iso
     * @param array $row
     *
     * @return \App\Models\Country
     */
    protected function country(string $iso, array $row): Models\Country
    {
        return Models\Country::create([
            'iso' => strtolower($iso),
            'name' => $row['name'],
            'native' => $row['native'],
            'default' => false,
            'enabled' => false
        ]);
    }

    /**
     * @return void
     */
    protected function update()
    {
        Models\Country::where('iso', 'es')->update([
            'default' => true,
            'enabled' => true
        ]);
    }
}
