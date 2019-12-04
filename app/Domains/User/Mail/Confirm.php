<?php declare(strict_types=1);

namespace App\Domains\User\Mail;

use App\Mails\MailAbstract;
use App\Models\User as Model;

class Confirm extends MailAbstract
{
    /**
     * @var \App\Models\User
     */
    public Model $user;

    /**
     * @var string
     */
    public string $url = '';

    /**
     * @var string
     */
    public $subject = '';

    /**
     * @var string
     */
    public $view = 'user.confirm';

    /**
     * @param \App\Models\User $user
     * @param string $hash
     *
     * @return self
     */
    public function __construct(Model $user, string $hash)
    {
        $this->subject = __('mail.user.confirm.subject');
        $this->user = $user;
        $this->url = routeWeb('user.confirm.finish', $hash);
    }
}
