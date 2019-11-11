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
        $this->truncate('country', 'state');

        foreach ($this->json('country-state') as $iso => $row) {
            $this->states($this->country($iso, $row), $row['states']);
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
     * @param \App\Models\Country $country
     * @param array $states
     *
     * @return void
     */
    protected function states(Models\Country $country, array $states)
    {
        $country_id = $country->id;
        $insert = [];

        foreach ($states as $name => $each) {
            if (empty($each)) {
                continue;
            }

            $insert[] = [
                'name' => $name,
                'country_id' => $country_id
            ];
        }

        $country->states()->insert($insert);
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

        Models\Country::where('iso', 'de')->update([
            'default' => false,
            'enabled' => true
        ]);
    }
}
