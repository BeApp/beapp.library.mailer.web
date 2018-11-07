<?php

namespace Beapp\Email\Core\Transport;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;

/**
 * Interface MailerTransport
 * @package Beapp\Email\Transport
 */
interface MailerTransport
{
    /**
     * Delivers the email to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param Mail $email
     * @throws MailerException
     */
    public function sendEmail(Mail $email): void;
}
