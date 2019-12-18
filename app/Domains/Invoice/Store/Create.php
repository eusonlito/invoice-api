<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\Invoice\Store;
use App\Models\Invoice as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Invoice
     */
    public function create(): Model
    {
        $this->row = new Model([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->factory(Store::class)->update();
    }
}
