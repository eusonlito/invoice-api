<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

use App\Models\InvoiceSerie as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\InvoiceSerie
     */
    public function create(): Model
    {
        return $this->factory(Store\Create::class)->create();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function certificate(): Model
    {
        return $this->factory(Store\Certificate::class)->update();
    }

    /**
     * @return string
     */
    public function css(): string
    {
        return $this->factory(Store\Css::class)->get();
    }

    /**
     * @return string
     */
    public function cssPreview(): string
    {
        return $this->factory(Store\Css::class)->preview();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function cssUpdate(): Model
    {
        return $this->factory(Store\Css::class)->update();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function numberNext(): Model
    {
        return $this->factory(Store\Number::class)->setNext();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->factory(Store\Delete::class)->delete();
    }
}
