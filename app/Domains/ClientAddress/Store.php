<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

use App\Models;
use App\Models\ClientAddress as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @param \App\Models\Client $client
     *
     * @return \App\Models\ClientAddress
     */
    public function create(Models\Client $client): Model
    {
        return $this->factory(Store\Create::class)->create($client);
    }

    /**
     * @return \App\Models\ClientAddress
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
