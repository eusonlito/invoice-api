<?php declare(strict_types=1);

namespace App\Domains\Client\Store;

use App\Domains\Client\Store;
use App\Models\Client as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Client
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
