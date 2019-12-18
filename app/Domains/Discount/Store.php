<?php declare(strict_types=1);

namespace App\Domains\Discount;

use App\Models\Discount as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Discount
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Discount
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
