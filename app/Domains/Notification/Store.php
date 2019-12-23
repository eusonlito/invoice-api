<?php declare(strict_types=1);

namespace App\Domains\Notification;

use App\Models\Notification as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return void
     */
    public function read(): void
    {
        $this->factory(Store\Read::class)->all();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }
}
