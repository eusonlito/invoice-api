<?php declare(strict_types=1);

namespace App\Services\Model\Payment;

use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Payment
     */
    public function create(): Models\Payment
    {
        $row = new Models\Payment([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Payment $row
     *
     * @return \App\Models\Payment
     */
    public function update(Models\Payment $row): Models\Payment
    {
        $row->name = $this->data['name'];
        $row->description = $this->data['description'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Payment');

        service()->log('payment', 'update', $this->user->id, ['payment_id' => $row->id]);

        return $row;
    }
}
