<?php declare(strict_types=1);

namespace App\Domains\Tax\Store;

use App\Domains\Tax\Store;
use App\Models\Tax as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Tax
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
