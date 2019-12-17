<?php declare(strict_types=1);

namespace App\Domains\Form;

class Store extends Store\StoreAbstract
{
    /**
     * @return array
     */
    public function contact(): array
    {
        return (new Store\Contact($this->user, null, $this->data))->contact();
    }
}
