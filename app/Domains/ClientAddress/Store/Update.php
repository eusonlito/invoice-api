<?php declare(strict_types=1);

namespace App\Domains\ClientAddress\Store;

use App\Models\ClientAddress as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\ClientAddress
     */
    public function update(): Model
    {
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

        $this->cacheFlush();

        service()->log('client_address', 'update', $this->user->id, ['client_address_id' => $this->row->id]);

        return $this->row;
    }
}
