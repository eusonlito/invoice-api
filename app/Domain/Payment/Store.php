<?php declare(strict_types=1);

namespace App\Domain\Payment;

use App\Exceptions;
use App\Models\Payment as Model;
use App\Domain\StoreAbstract;

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
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
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

    /**
     * @param \App\Models\Payment $row
     *
     * @return void
     */
    public function delete(Model $row): void
    {
        if ($row->clients()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-clients'));
        }

        if ($row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $row->delete();

        $this->cacheFlush('Payment');

        service()->log('payment', 'delete', $this->user->id);
    }
}
