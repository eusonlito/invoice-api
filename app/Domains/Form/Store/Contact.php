<?php declare(strict_types=1);

namespace App\Domains\Form\Store;

use App\Services\Mail\Mailer;

class Contact extends StoreAbstract
{
    /**
     * @return array
     */
    public function contact(): array
    {
        Mailer::queue(new Mail\Contact($this->data), null, [config('mail.from.address')]);

        return $this->data;
    }
}
