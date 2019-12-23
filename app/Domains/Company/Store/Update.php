<?php declare(strict_types=1);

namespace App\Domains\Company\Store;

use App\Models\Company as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        $this->row->name = $this->data['name'];
        $this->row->address = $this->data['address'];
        $this->row->city = $this->data['city'];
        $this->row->postal_code = $this->data['postal_code'];
        $this->row->tax_number = $this->data['tax_number'];
        $this->row->email = $this->data['email'];
        $this->row->phone = $this->data['phone'];
        $this->row->state = $this->data['state'];
        $this->row->country_id = $this->data['country_id'];
        $this->row->user_id = $this->user->id;

        $this->row->save();

        $this->cacheFlush();

        service()->log('company', 'update', $this->user->id, ['company_id' => $this->row->id]);

        return $this->row;
    }
}
