<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Models\Product as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Product
     */
    public function create(): Model
    {
        return (new Store\Create($this->user, null, $this->data))->create();
    }

    /**
     * @return \App\Models\Product
     */
    public function update(): Model
    {
        return (new Store\Update($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        (new Store\Delete($this->user, $this->row))->delete();
    }
}
