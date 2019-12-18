<?php declare(strict_types=1);

namespace App\Domains\Company;

use App\Models\Company as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }
}
