<?php declare(strict_types=1);

namespace App\Domains\Notification\Store;

use App\Domains\Notification\Store;
use App\Models\Notification as Model;

class Create extends StoreAbstract
{
    /**
     * @return \App\Models\Notification
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
