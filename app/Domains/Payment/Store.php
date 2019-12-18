<?php declare(strict_types=1);

namespace App\Domains\Payment;

use App\Models\Payment as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Payment
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Payment
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
