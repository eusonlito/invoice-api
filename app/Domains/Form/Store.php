<?php declare(strict_types=1);

namespace App\Domains\Form;

class Store extends Store\StoreAbstract
{
    /**
     * @return array
     */
    public function contact(): array
    {
        return $this->factory(Store\Contact::class)->contact();
    }
}
