<?php declare(strict_types=1);

namespace App\Domains\User\Mail;

use App\Mails\MailAbstract;
use App\Models;
use App\Models\User as Model;

class PasswordReset extends MailAbstract
{
    /**
     * @var \App\Models\User
     */
    public Model $user;

    /**
     * @var \App\Models\UserPasswordReset
     */
    public Models\UserPasswordReset $reset;

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
    public $view = 'user.password-reset';

    /**
     * @param \App\Models\User $user
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return self
     */
    public function __construct(Model $user, Models\UserPasswordReset $reset)
    {
        $this->subject = __('mail.user.password-reset.subject');
        $this->user = $user;
        $this->reset = $reset;
        $this->url = routeWeb('user.password.reset.finish', $reset->hash);
    }
}
