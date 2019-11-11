<?php declare(strict_types=1);

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class MailAbstract extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return self
     */
    public function build(): self
    {
        return $this->view('email.pages.'.$this->view);
    }

    /**
     * @param string $to
     *
     * @return void
     */
    public function setTo(string $to)
    {
        foreach (explode(',', $to) as $email) {
            $this->to(trim($email));
        }
    }

    /**
     * @param mixed $file
     *
     * @return self
     */
    public function file($file): self
    {
        if (is_string($file)) {
            return $this->attach($file);
        }

        return $this->attach($file->getRealPath(), [
            'as' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
        ]);
    }
}
