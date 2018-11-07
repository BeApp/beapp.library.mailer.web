<?php

namespace Beapp\Email\Core\Transport;

use Beapp\Email\Core\Mail;
use Psr\Log\LoggerInterface;

/**
 * Only log the given {@link Mail}.
 * This is useful in development environment to prevent spamming users
 *
 * @package Beapp\Email\Transport
 */
class NoopMailerTransport implements MailerTransport
{

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * NoopMailerTransport constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendEmail(Mail $email): void
    {
        $this->logger->debug("NOOP sending email", ['email' => $email]);
    }
}
