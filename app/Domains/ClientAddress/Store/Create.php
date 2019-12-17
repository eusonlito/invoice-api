<?php declare(strict_types=1);

namespace App\Domains\ClientAddress\Store;

use App\Domains\ClientAddress\Store;
use App\Models;
use App\Models\ClientAddress as Model;

class Create extends StoreAbstract
{
    /**
     * @param \App\Models\Client $client
     *
     * @return \App\Models\ClientAddress
     */
    public function create(Models\Client $client): Model
    {
        $this->row = new Model([
            'client_id' => $client->id,
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return (new Store($this->user, $this->row, $this->data))->update();
    }
}
