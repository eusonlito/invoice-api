<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use App\Domains\InvoiceSerie\Store;
use App\Models\InvoiceSerie as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceSerie
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
