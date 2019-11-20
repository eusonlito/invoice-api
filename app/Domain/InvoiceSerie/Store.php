<?php declare(strict_types=1);

namespace App\Domain\InvoiceSerie;

use App\Exceptions;
use App\Models\InvoiceSerie as Model;
use App\Domain\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceSerie
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
     * @param \App\Models\InvoiceSerie $row
     *
     * @return \App\Models\InvoiceSerie
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $row->name = $this->data['name'];
        $row->number_prefix = $this->data['number_prefix'];
        $row->number_fill = (int)$this->data['number_fill'];
        $row->number_next = (int)$this->data['number_next'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('InvoiceSerie');

        service()->log('invoice_serie', 'update', $this->user->id, ['invoice_serie_id' => $row->id]);

        return $row;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return \App\Models\InvoiceSerie
     */
    public function cssUpdate(Model $row): Model
    {
        $row->css = StoreCss::save($row, $this->data['css']);
        $row->save();

        $this->cacheFlush('InvoiceSerie');

        service()->log('invoice_serie', 'update-css', $this->user->id, ['invoice_serie_id' => $row->id]);

        return $row;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return void
     */
    public function delete(Model $row): void
    {
        if ($row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $row->delete();

        if ($row->css) {
            Model::disk()->delete($row->css);
        }

        $this->cacheFlush('InvoiceSerie');

        service()->log('invoice_serie', 'delete', $this->user->id);
    }
}
