<?php declare(strict_types=1);

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use App\Mails\MailAbstract;
use App\Models;

class Mailer
{
    /**
     * @param \App\Mails\MailAbstract $mail
     * @param ?\App\Models\User $user
     * @param array $emails = []
     *
     * @return void
     */
    public static function queue(MailAbstract $mail, ?Models\User $user, array $emails = [])
    {
        Mail::queue(static::options($mail, $user, $emails));
    }

    /**
     * @param \App\Mails\MailAbstract $mail
     * @param ?\App\Models\User $user
     * @param array $emails = []
     *
     * @return void
     */
    public static function send(MailAbstract $mail, ?Models\User $user, array $emails = [])
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
    protected static function options(MailAbstract $mail, ?Models\User $user, array $emails): MailAbstract
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
