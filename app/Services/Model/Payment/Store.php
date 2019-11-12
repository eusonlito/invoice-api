<?php declare(strict_types=1);

namespace App\Services\Model\Payment;

use App\Models\Payment as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Payment
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
     * @param \App\Models\Payment $row
     *
     * @return \App\Models\Payment
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::where('default', true)->update(['default' => false]);
        }

        $row->name = $this->data['name'];
        $row->description = $this->data['description'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Payment');

        service()->log('payment', 'update', $this->user->id, ['payment_id' => $row->id]);

        return $row;
    }
}
