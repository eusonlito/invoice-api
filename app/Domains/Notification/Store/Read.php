<?php declare(strict_types=1);

namespace App\Domains\Notification\Store;

use App\Models\Notification as Model;

class Read extends StoreAbstract
{
    /**
     * @return void
     */
    public function all(): void
    {
        Model::byCompany($this->user->company)->unread()->update(['readed_at' => date('Y-m-d H:i:s')]);

        $this->cacheFlush();
    }
}
