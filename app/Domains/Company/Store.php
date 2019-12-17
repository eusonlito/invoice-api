<?php declare(strict_types=1);

namespace App\Domains\Company;

use App\Models\Company as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Company
     */
    public function create(): Model
    {
        return (new Store\Create($this->user, null, $this->data))->create();
    }

    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        return (new Store\Update($this->user, $this->row, $this->data))->update();
    }
}
