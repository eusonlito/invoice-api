<?php declare(strict_types=1);

namespace App\Domains\Discount\Store;

use App\Domains\Discount\Store;
use App\Models\Discount as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Discount
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
