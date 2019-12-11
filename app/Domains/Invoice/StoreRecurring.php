<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Support\Collection;
use App\Domains\StoreAbstract;
use App\Models\Invoice as Model;

class StoreRecurring extends StoreAbstract
{
    /**
     * @return void
     */
    public static function pending()
    {
        foreach (Model::pendingToRecurring()->cursor() as $row) {
            event(new Event\Recurring($row));
        }
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return void
     */
    public function invoice(Model $row)
    {
        $row->setRelations([]);

        $this->row = (new StoreDuplicate($this->user, $row->toArray()))->invoice($row);

        $this->row->date_at = $this->row->recurring_at;
        $this->row->recurring_at = $this->row->recurring->next($this->row->date_at);

        $this->row->save();

        $row->recurring_at = null;
        $row->save();

        service()->log('invoice', 'recurring', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }
}
