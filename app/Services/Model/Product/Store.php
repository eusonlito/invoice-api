<?php declare(strict_types=1);

namespace App\Services\Model\Product;

use App\Exceptions;
use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Product
     */
    public function create(): Models\Product
    {
        $row = new Models\Product([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Product $row
     *
     * @return \App\Models\Product
     */
    public function update(Models\Product $row): Models\Product
    {
        $this->row = $row;

        $this->check();

        $row->name = $this->data['name'];
        $row->reference = $this->data['reference'];
        $row->price = (float)abs($this->data['price']);
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('product');

        service()->log('product', 'update', $this->user->id, ['product_id' => $row->id]);

        return $row;
    }

    /**
     * @return void
     */
    protected function check()
    {
        if (empty($this->data['reference'])) {
            return;
        }

        $exists = Models\Product::where('reference', $this->data['reference'])
            ->where('id', '!=', $this->row->id)
            ->byCompany($this->user->company)
            ->count();

        if ($exists) {
            throw new Exceptions\ValidatorException(__('validator.reference-duplicated'));
        }
    }
}
