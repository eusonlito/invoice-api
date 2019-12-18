<?php declare(strict_types=1);

namespace App\Domains\Payment\Store;

use App\Domains\Payment\Store;
use App\Models\Payment as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Payment
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
