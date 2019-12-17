<?php declare(strict_types=1);

namespace App\Domains\Product\Store;

use App\Exceptions;
use App\Models\Product as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Product
     */
    public function update(): Model
    {
        $this->check();

        $this->row->name = $this->data['name'];
        $this->row->reference = $this->data['reference'];
        $this->row->price = (float)abs($this->data['price']);
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush('Product', 'Invoice');

        service()->log('product', 'update', $this->user->id, ['product_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function check()
    {
        if (empty($this->data['reference'])) {
            return;
        }

        $exists = Model::where('reference', $this->data['reference'])
            ->where('id', '!=', $this->row->id)
            ->byCompany($this->user->company)
            ->count();

        if ($exists) {
            throw new Exceptions\ValidatorException(__('validator.reference-duplicated'));
        }
    }
}
