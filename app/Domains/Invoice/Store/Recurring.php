<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\Invoice\Event\Recurring as Event;
use App\Domains\Invoice\Store;
use App\Models\Invoice as Model;

class Recurring extends StoreAbstract
{
    /**
     * @return void
     */
    public static function pending()
    {
        foreach (Model::pendingToRecurring()->cursor() as $row) {
            event(new Event($row));
        }
    }

    /**
     * @return void
     */
    public function recurring()
    {
        $previous = clone $this->row;
        $previous->setRelations([]);

        $this->row = $this->factory(Store::class)->duplicate();

        $this->row->date_at = $this->row->recurring_at;
        $this->row->recurring_at = $this->row->recurring->next($this->row->date_at);

        $this->row->save();

        $previous->recurring_at = null;
        $previous->save();

        service()->log('invoice', 'recurring', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }
}
