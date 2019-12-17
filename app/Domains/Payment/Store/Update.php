<?php declare(strict_types=1);

namespace App\Domains\Payment\Store;

use App\Models\Payment as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Payment
     */
    public function update(): Model
    {
        if ($this->data['default'] && empty($this->row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $this->row->name = $this->data['name'];
        $this->row->description = $this->data['description'];
        $this->row->default = (bool)$this->data['default'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush('Payment', 'Invoice');

        service()->log('payment', 'update', $this->user->id, ['payment_id' => $this->row->id]);

        return $this->row;
    }
}
