<?php

namespace Beapp\Email\Core\Transport;

use Beapp\Email\Core\Mail;

class MockMailerTransport implements MailerTransport
{

    /** @var Mail|null */
    private $lastMail;

    /**
     * Delivers the email to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param Mail $email
     */
    public function sendEmail(Mail $email): void
    {
        $this->lastMail = $email;
    }

    /**
     * @return mixed
     */
    public function getLastMail()
    {
        $lastMail = $this->lastMail;
        $this->lastMail = null;
        return $lastMail;
    }
}
