<?php declare(strict_types=1);

namespace App\Services\Model\Company;

use App\Models\Company as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        if (!($row = $this->user->company)) {
            $row = new Model;
        }

        $row->name = $this->data['name'];
        $row->address = $this->data['address'];
        $row->city = $this->data['city'];
        $row->postal_code = $this->data['postal_code'];
        $row->tax_number = $this->data['tax_number'];
        $row->email = $this->data['email'];
        $row->phone = $this->data['phone'];
        $row->state_id = (int)$this->data['state_id'];
        $row->user_id = $this->user->id;

        $row->save();

        $this->user->company_id = $row->id;
        $this->user->save();

        $this->cacheFlush('Company');
        $this->cacheFlush('User');

        service()->log('company', 'update', $this->user->id, ['company_id' => $row->id]);

        return $row;
    }
}
