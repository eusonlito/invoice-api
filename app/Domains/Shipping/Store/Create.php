<?php declare(strict_types=1);

namespace App\Domains\Shipping\Store;

use App\Domains\Shipping\Store;
use App\Models\Shipping as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Shipping
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
