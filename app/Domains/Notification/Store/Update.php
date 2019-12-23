<?php declare(strict_types=1);

namespace App\Domains\Notification\Store;

use App\Domains\Notification\Store;
use App\Models\Notification as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Notification
     */
    public function update(): Model
    {
        $this->row->code = $this->data['code'];
        $this->row->title = $this->data['title'];
        $this->row->status = $this->data['status'];
        $this->row->invoice_id = $this->data['invoice_id'] ?? null;

        $this->row->save();

        $this->cacheFlush();

        return $this->row;
    }
}
