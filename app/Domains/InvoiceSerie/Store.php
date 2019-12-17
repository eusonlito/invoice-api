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
        return (new Store\Create($this->user, null, $this->data))->create();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        return (new Store\Update($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function certificate(): Model
    {
        return (new Store\Certificate($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return string
     */
    public function css(): string
    {
        return (new Store\Css($this->user, $this->row, $this->data))->get();
    }

    /**
     * @return string
     */
    public function cssPreview(): string
    {
        return (new Store\Css($this->user, $this->row, $this->data))->preview();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function cssUpdate(): Model
    {
        return (new Store\Css($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function numberNext(): Model
    {
        return (new Store\Number($this->user, $this->row, $this->data))->setNext();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        (new Store\Delete($this->user, $this->row))->delete();
    }
}
