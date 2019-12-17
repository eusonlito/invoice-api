<?php declare(strict_types=1);

namespace App\Domains\InvoiceStatus\Store;

use App\Domains\InvoiceStatus\Store;
use App\Models\InvoiceStatus as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceStatus
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
