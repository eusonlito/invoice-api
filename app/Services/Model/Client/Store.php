<?php declare(strict_types=1);

namespace App\Services\Model\Client;

use App\Models;
use App\Models\Client as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Client
     */
    public function create(): Model
    {
        $row = new Model([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Client $row
     *
     * @return \App\Models\Client
     */
    public function update(Model $row): Model
    {
        $this->row = $row;

        $this->row->name = $this->data['name'];
        $this->row->phone = $this->data['phone'];
        $this->row->email = $this->data['email'];
        $this->row->contact_name = $this->data['contact_name'];
        $this->row->contact_surname = $this->data['contact_surname'];
        $this->row->web = $this->data['web'];
        $this->row->tax_number = $this->data['tax_number'];
        $this->row->contact_phone = $this->data['contact_phone'];
        $this->row->contact_email = $this->data['contact_email'];
        $this->row->comment = $this->data['comment'];

        $this->discount();
        $this->payment();
        $this->shipping();
        $this->tax();

        $this->row->save();

        $this->cacheFlush('Client');

        service()->log('client', 'update', $this->user->id, ['client_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function discount()
    {
        if (empty($this->data['discount_id'])) {
            return $this->row->discount_id = null;
        }

        $this->row->discount_id = Models\Discount::select('id')
            ->byId($this->data['discount_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function payment()
    {
        if (empty($this->data['payment_id'])) {
            return $this->row->payment_id = null;
        }

        $this->row->payment_id = Models\Payment::select('id')
            ->byId($this->data['payment_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function shipping()
    {
        if (empty($this->data['shipping_id'])) {
            return $this->row->shipping_id = null;
        }

        $this->row->shipping_id = Models\Shipping::select('id')
            ->byId($this->data['shipping_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function tax()
    {
        if (empty($this->data['tax_id'])) {
            return $this->row->tax_id = null;
        }

        $this->row->tax_id = Models\Tax::select('id')
            ->byId($this->data['tax_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }
}
