<?php declare(strict_types=1);

namespace App\Domains\Shipping;

use App\Models\Shipping as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Shipping
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Shipping
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->factory(Store\Delete::class)->delete();
    }
}
