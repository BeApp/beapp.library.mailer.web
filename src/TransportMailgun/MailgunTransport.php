<?php

namespace Beapp\Email\Transport;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use Mailgun\Mailgun;

class MailgunTransport implements MailerTransport
{

    /** @var Mailgun $mailgun */
    private $mailgun;
    /** @var string */
    private $domain;
    /** @var string */
    private $defaultSenderMail;
    /** @var string */
    private $defaultSenderName;
    /** @var string|null */
    private $forceToEmail;

    /**
     * MailgunClient constructor.
     * @param string $key
     * @param string $domain
     * @param string $defaultSenderMail
     * @param string $defaultSenderName
     * @param null $forceToEmail
     */
    public function __construct(string $key, string $domain, string $defaultSenderMail, string $defaultSenderName, $forceToEmail = null)
    {
        $this->mailgun = Mailgun::create($key);

        $this->domain = $domain;
        $this->defaultSenderMail = $defaultSenderMail;
        $this->defaultSenderName = $defaultSenderName;
        $this->forceToEmail = $forceToEmail;
    }

    /**
     * Delivers the email to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param Mail $email
     * @throws MailerException
     */
    public function sendEmail(Mail $email): void
    {
        $mailParams = [];
        $mailParams['from'] = $email->getSenderEmail();

        // Keep this here (in addition of the one in MailerService) as a fail-safe.
        // If the "Mailgun Redirect To Email" is given we force the redirection to this email
        if (!empty($this->forceToEmail)) {
            $email->setRecipientEmail($this->forceToEmail);
        }

        if ($email->getRecipientName()) {
            $mailParams['to'] = $email->getRecipientName() . ' <' . $email->getRecipientEmail() . '>';
        } else {
            $mailParams['to'] = $email->getRecipientEmail();
        }
        $mailParams['subject'] = $email->getSubject();
        $mailParams['text'] = $email->getTextContent();
        $mailParams['html'] = $email->getHtmlContent();

        try {
            $this->mailgun->messages()->send($this->domain, $mailParams);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through Mailgun", 0, $e);
        }
    }
}
