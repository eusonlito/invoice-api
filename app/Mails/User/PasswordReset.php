<?php declare(strict_types=1);

namespace App\Mails\User;

use App\Mails\MailAbstract;
use App\Models;

class PasswordReset extends MailAbstract
{
    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var \App\Models\UserPasswordReset
     */
    public $reset;

    /**
     * @var string
     */
    public $url = '';

    /**
     * @var string
     */
    public $subject = '';

    /**
     * @var string
     */
    public $view = 'user.password-reset';

    /**
     * @param \App\Models\User $user
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return self
     */
    public function __construct(Models\User $user, Models\UserPasswordReset $reset)
    {
        $this->subject = __('mail.user.password-reset.subject');
        $this->user = $user;
        $this->reset = $reset;
        $this->url = routeWeb('user.password.reset.finish', $reset->hash);
    }
}
