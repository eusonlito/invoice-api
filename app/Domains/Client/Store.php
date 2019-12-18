<?php declare(strict_types=1);

namespace App\Domains\Client;

use App\Models\Client as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Client
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Client
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
