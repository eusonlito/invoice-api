<?php declare(strict_types=1);

namespace App\Domains\Company;

use App\Models;
use App\Models\Company as Model;
use App\Domains\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        $this->row = $this->user->company ?: (new Model);

        $this->row->name = $this->data['name'];
        $this->row->address = $this->data['address'];
        $this->row->city = $this->data['city'];
        $this->row->postal_code = $this->data['postal_code'];
        $this->row->tax_number = $this->data['tax_number'];
        $this->row->email = $this->data['email'];
        $this->row->phone = $this->data['phone'];
        $this->row->state = $this->data['state'];
        $this->row->country_id = $this->data['country_id'];
        $this->row->user_id = $this->user->id;

        $this->row->save();

        $this->relations();

        $this->user->company_id = $this->row->id;
        $this->user->save();

        $this->cacheFlush('Company');
        $this->cacheFlush('User');

        service()->log('company', 'update', $this->user->id, ['company_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function relations()
    {
        if ($this->user->company) {
            return;
        }

        $this->discount();
        $this->invoiceSerie();
        $this->invoiceStatus();
        $this->payment();
        $this->tax();
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

        $this->cacheFlush('Discount');
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

        $this->cacheFlush('InvoiceSerie');
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

        $this->cacheFlush('InvoiceStatus');
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

        $this->cacheFlush('Payment');
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

        $this->cacheFlush('Tax');
    }
}
