<?php declare(strict_types=1);

namespace App\Domains\Company\Store;

use App\Domains\Company\Store;
use App\Exceptions\NotAllowedException;
use App\Models;
use App\Models\Company as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function create(): Model
    {
        if ($this->user->company) {
            throw new NotAllowedException();
        }

        $this->row = (new Store($this->user, new Model(['user_id' => $this->user->id]), $this->data))->update();

        $this->user->company_id = $this->row->id;
        $this->user->save();

        $this->discount();
        $this->invoiceSerie();
        $this->invoiceStatus();
        $this->payment();
        $this->tax();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function discount()
    {
        Models\Discount::insert([
            [
                'name' => 'IRPF 15%',
                'type' => 'percent',
                'value' => 15,
                'default' => true,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id
            ],

            [
                'name' => 'IRPF 21%',
                'type' => 'percent',
                'value' => 21,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id
            ]
        ]);
    }

    /**
     * @return void
     */
    protected function invoiceSerie()
    {
        Models\InvoiceSerie::insert([
            [
                'name' => 'Factura',
                'number_prefix' => date('Y-'),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => true,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],
            [
                'name' => 'Presupuesto',
                'number_prefix' => ('PRE-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],
            [
                'name' => 'Proforma',
                'number_prefix' => ('PRO-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],
            [
                'name' => 'Rectificativa',
                'number_prefix' => ('REC-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function invoiceStatus()
    {
        Models\InvoiceStatus::insert([
            [
                'name' => 'Creada',
                'order' => 1,
                'paid' => false,
                'default' => true,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],

            [
                'name' => 'Enviada',
                'order' => 2,
                'paid' => false,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],

            [
                'name' => 'Pagada',
                'order' => 3,
                'paid' => true,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ],

            [
                'name' => 'Rechazada',
                'order' => 4,
                'paid' => false,
                'default' => false,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id,
            ]
        ]);
    }

    /**
     * @return void
     */
    protected function payment()
    {
        Models\Payment::insert([
            [
                'name' => 'Transferencia Bancaria',
                'description' => 'Realizar ingreso en la siguiente cuenta bancaria indicando el número de factura.',
                'default' => true,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function tax()
    {
        Models\Tax::insert([
            [
                'name' => 'IVA 21%',
                'value' => 21,
                'default' => true,
                'enabled' => true,
                'company_id' => $this->row->id,
                'user_id' => $this->user->id
            ],
        ]);
    }
}
