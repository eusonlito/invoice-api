<?php declare(strict_types=1);

namespace App\Mails\User;

use App\Mails\MailAbstract;
use App\Models;

class Confirm extends MailAbstract
{
    /**
     * @var \App\Models\User
     */
    public $user;

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
    public $view = 'user.confirm';

    /**
     * @param \App\Models\User $user
     * @param string $hash
     *
     * @return self
     */
    public function __construct(Models\User $user, string $hash)
    {
        $this->subject = __('mail.user.confirm.subject');
        $this->user = $user;
        $this->url = routeWeb('user.confirm.finish', $hash);
    }
}
