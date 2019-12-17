<?php declare(strict_types=1);

namespace App\Domains\User;

use App\Models;
use App\Models\User as Model;
use App\Services\Mail\Mailer;

class Mail
{
    /**
     * @param \App\Models\User $row
     *
     * @return void
     */
    public static function signup(Model $row): void
    {
        Mailer::queue(new Mail\Signup($row, encrypt($row->id.'|'.time())), $row, [$row->user]);
    }

    /**
     * @param \App\Models\User $row
     *
     * @return void
     */
    public static function confirmStart(Model $row): void
    {
        Mailer::queue(new Mail\Confirm($row, encrypt($row->id.'|'.microtime(true))), $row, [$row->user]);
    }

    /**
     * @param \App\Models\User $row
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return void
     */
    public static function passwordResetStart(Model $row, Models\UserPasswordReset $reset): void
    {
        Mailer::queue(new Mail\PasswordReset($row, $reset), $row, [$row->user]);
    }
}
