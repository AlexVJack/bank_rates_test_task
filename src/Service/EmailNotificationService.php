<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationService
{
    public function __construct(private MailerInterface $mailer) {
    }

    public function sendNotification(string $subject, string $body)
    {
        $email = (new Email())
            ->from('your-email@example.com')
            ->to('recipient@example.com')
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
