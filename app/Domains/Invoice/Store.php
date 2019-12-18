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
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function duplicate(): Model
    {
        return $this->factory(Store\Duplicate::class)->duplicate();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function paid(): Model
    {
        return $this->factory(Store\Paid::class)->paid();
    }

    /**
     * @return \App\Models\Invoice
     */
    public function recurring(): Model
    {
        return $this->factory(Store\Recurring::class)->recurring();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->factory(Store\Delete::class)->delete();
    }
}
