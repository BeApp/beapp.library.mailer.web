<?php

namespace Beapp\Email\Core;

use Beapp\Email\Core\Template\MailTemplate;
use Beapp\Email\Core\Transport\MailerTransport;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class MailerService
 * @package Beapp\Email
 */
class MailerService
{

    /** @var MailerTransport $mailerTransport */
    private $mailerTransport;

    /** @var RouterInterface $router */
    private $router;

    /** @var TranslatorInterface $translator */
    private $translator;

    /** @var EngineInterface $templating */
    private $templating;

    /** @var string $defaultSenderMail */
    private $defaultSenderMail;

    /** @var string $defaultSenderName */
    private $defaultSenderName;

    /**
     * MailerService constructor.
     * @param MailerTransport $mailerTransport
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param EngineInterface $templating
     * @param string $defaultSenderMail
     * @param string $defaultSenderName
     */
    public function __construct(
        MailerTransport $mailerTransport,
        RouterInterface $router,
        TranslatorInterface $translator,
        EngineInterface $templating,
        string $defaultSenderMail,
        string $defaultSenderName
    ) {
        $this->mailerTransport = $mailerTransport;
        $this->router = $router;
        $this->translator = $translator;
        $this->templating = $templating;

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
        $mail = $mailTemplate->build($this->router, $this->translator, $this->templating);
        if (!$mail->getSenderEmail()) {
            $mail->setSenderEmail($this->defaultSenderMail);
            $mail->setSenderName($this->defaultSenderName);
        }

        $this->mailerTransport->sendEmail($mail);
    }

}
