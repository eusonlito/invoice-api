<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring\Store;

use App\Domains\InvoiceRecurring\Store;
use App\Models\InvoiceRecurring as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceRecurring
     */
    public function create(): Model
    {
        $this->row = new Model([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return (new Store($this->user, $this->row, $this->data))->update();
    }
}
