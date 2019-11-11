<?php declare(strict_types=1);

namespace App\Services\Model\ClientAddress;

use App\Models;
use App\Models\ClientAddress as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @param \App\Models\Client $client
     *
     * @return \App\Models\ClientAddress
     */
    public function create(Models\Client $client): Model
    {
        $row = new Models\ClientAddress([
            'client_id' => $client->id,
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\ClientAddress $row
     *
     * @return \App\Models\ClientAddress
     */
    public function update(Model $row): Model
    {
        $this->row = $row;

        $this->row->name = $this->data['name'];
        $this->row->address = $this->data['address'];
        $this->row->city = $this->data['city'];
        $this->row->state = $this->data['state'];
        $this->row->postal_code = $this->data['postal_code'];
        $this->row->country = $this->data['country'];
        $this->row->phone = $this->data['phone'];
        $this->row->email = $this->data['email'];
        $this->row->comment = $this->data['comment'];
        $this->row->tax_number = $this->data['tax_number'];
        $this->row->billing = (bool)$this->data['billing'];
        $this->row->shipping = (bool)$this->data['shipping'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush('ClientAddress');

        service()->log('client_address', 'update', $this->user->id, ['client_address_id' => $this->row->id]);

        return $this->row;
    }
}
