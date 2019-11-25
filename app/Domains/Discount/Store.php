<?php declare(strict_types=1);

namespace App\Domains\Discount;

use App\Exceptions;
use App\Models\Discount as Model;
use App\Domains\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Discount
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
     * @param \App\Models\Discount $row
     *
     * @return \App\Models\Discount
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $row->name = $this->data['name'];
        $row->type = $this->data['type'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Discount', 'Invoice');

        service()->log('discount', 'update', $this->user->id, ['discount_id' => $row->id]);

        return $row;
    }

    /**
     * @param \App\Models\Discount $row
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

        $this->cacheFlush('Discount', 'Invoice');

        service()->log('discount', 'delete', $this->user->id);
    }
}
