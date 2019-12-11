<?php declare(strict_types=1);

namespace App\Domains\Form\Mail;

use App\Mails\MailAbstract;

class Contact extends MailAbstract
{
    /**
     * @var array
     */
    public array $data = [];

    /**
     * @var string
     */
    public $subject = '';

    /**
     * @var string
     */
    public $view = 'form.contact';

    /**
     * @param array $data
     *
     * @return self
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->subject = __('mail.form.contact.subject', ['name' => $data['name']]);
    }
}
