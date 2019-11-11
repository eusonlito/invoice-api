<?php declare(strict_types=1);

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use App\Mails;
use App\Models;

class Mailer
{
    /**
     * @param \App\Models\User $user
     * @param string $hash
     *
     * @return void
     */
    public static function userConfirm(Models\User $user, string $hash)
    {
        static::queue(new Mails\User\Confirm($user, $hash), $user, [$user->user]);
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return void
     */
    public static function userPasswordReset(Models\User $user, Models\UserPasswordReset $reset)
    {
        static::queue(new Mails\User\PasswordReset($user, $reset), $user, [$user->user]);
    }

    /**
     * @param \App\Models\User $user
     * @param string $hash
     *
     * @return void
     */
    public static function userSignup(Models\User $user, string $hash)
    {
        static::queue(new Mails\User\Signup($user, $hash), $user, [$user->user]);
    }

    /**
     * @param \App\Mails\MailAbstract $mail
     * @param ?\App\Models\User $user
     * @param array $emails
     *
     * @return void
     */
    protected static function queue(Mails\MailAbstract $mail, ?Models\User $user, array $emails)
    {
        Mail::queue(static::options($mail, $user, $emails));
    }

    /**
     * @param \App\Mails\MailAbstract $mail
     * @param ?\App\Models\User $user
     * @param array $emails
     *
     * @return void
     */
    protected static function send(Mails\MailAbstract $mail, ?Models\User $user, array $emails)
    {
        Mail::send(static::options($mail, $user, $emails));
    }

    /**
     * @param \App\Mails\MailAbstract $mail
     * @param ?\App\Models\User $user
     * @param array $emails
     *
     * @return \App\Mails\MailAbstract
     */
    protected static function options(Mails\MailAbstract $mail, ?Models\User $user, array $emails): Mails\MailAbstract
    {
        $mail->to(static::filter($emails));
        $mail->locale($user->language->iso ?: app()->getLocale());

        return $mail;
    }

    /**
     * @param array $emails
     *
     * @return array
     */
    protected static function filter(array $emails): array
    {
        return array_values(array_unique(array_filter(array_map('trim', $emails), static function ($value) {
            return $value && filter_var($value, FILTER_VALIDATE_EMAIL);
        })));
    }
}
