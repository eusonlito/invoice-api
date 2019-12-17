<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use App\Models\Invoice as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\Invoice
     */
    public function create(): Model
    {
        return (new Store\Create($this->user, null, $this->data))->create();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function update(): Model
    {
        return (new Store\Update($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function duplicate(): Model
    {
        return (new Store\Duplicate($this->user, $this->row, $this->data))->duplicate();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function paid(): Model
    {
        return (new Store\Paid($this->user, $this->row, $this->data))->paid();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function recurring(): Model
    {
        return (new Store\Recurring($this->user, $this->row, $this->data))->recurring();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        (new Store\Delete($this->user, $this->row))->delete();
    }
}
