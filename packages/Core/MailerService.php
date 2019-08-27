<?php

namespace Beapp\Email\Core;

use Beapp\Email\Core\Context\MailContextFactory;
use Beapp\Email\Core\Template\MailTemplate;
use Beapp\Email\Core\Transport\MailerTransport;

/**
 * Class MailerService
 * @package Beapp\Email
 */
class MailerService
{

    /** @var MailerTransport $mailerTransport */
    private $mailerTransport;

    /** @var MailContextFactory */
    private $mailContextFactory;

    /** @var string $defaultSenderMail */
    private $defaultSenderMail;

    /** @var string $defaultSenderName */
    private $defaultSenderName;

    /**
     * MailerService constructor.
     * @param MailerTransport $mailerTransport
     * @param MailContextFactory $mailContextFactory
     * @param string $defaultSenderMail
     * @param string $defaultSenderName
     */
    public function __construct(
        MailerTransport $mailerTransport,
        MailContextFactory $mailContextFactory,
        string $defaultSenderMail,
        string $defaultSenderName
    )
    {
        $this->mailerTransport = $mailerTransport;
        $this->mailContextFactory = $mailContextFactory;
        $this->defaultSenderMail = $defaultSenderMail;
        $this->defaultSenderName = $defaultSenderName;
    }

    /**
     * Build a {@link Mail} instance from the given {@link MailTemplate} and dispatch it to the configured {@link MailerTransport}.
     * The {@link MailerTransport} offers an abstraction to send an email thought different channel.
     *
     * @param MailTemplate $mailTemplate
     * @throws MailerException
     */
    public function sendMail(MailTemplate $mailTemplate)
    {
        $mail = $mailTemplate->build($this->mailContextFactory->buildContext());

        if (!$mail->getSenderEmail()) {
            $mail->setSenderEmail($this->defaultSenderMail);
            $mail->setSenderName($this->defaultSenderName);
        }

        $this->mailerTransport->sendEmail($mail);
    }

}
