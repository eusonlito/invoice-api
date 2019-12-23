<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\Invoice\Event\Recurring as Event;
use App\Domains\Invoice\Store;
use App\Domains\Notification\Store as NotificationStore;
use App\Models\Invoice as Model;

class Recurring extends StoreAbstract
{
    /**
     * @return void
     */
    public static function pending()
    {
        foreach (Model::pendingToRecurring()->pluck('id') as $id) {
            event(new Event($id));
        }
    }

    /**
     * @return void
     */
    public function recurring()
    {
        $previous = clone $this->row;

        $this->row = $this->factory(Store::class, [
            'invoice_serie_id' => $this->row->invoice_serie_id
        ])->duplicate();

        $this->row->date_at = $this->row->recurring_at;
        $this->row->recurring_at = $this->row->recurring->next($this->row->date_at);

        $this->row->save();

        $previous->recurring_at = null;
        $previous->save();

        service()->log('invoice', 'recurring', $this->user->id, ['invoice_id' => $this->row->id]);

        $this->notification();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function notification()
    {
        (new NotificationStore($this->user, null, [
            'code' => 'invoice.recurring',
            'title' => __('notification.invoice.recurring.title', ['number' => $this->row->number]),
            'status' => 'success',
            'invoice_id' => $this->row->id
        ]))->create();
    }
}
