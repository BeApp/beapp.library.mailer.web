<?php

namespace Beapp\Email\Transport\Mailgun;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use Mailgun\Mailgun;
use Symfony\Component\HttpFoundation\File\File;

class MailgunTransport implements MailerTransport
{

    /** @var string */
    private $key;
    /** @var string */
    private $domain;
    /** @var Mailgun $mailgun */
    private $client;

    /**
     * @param string $key
     * @param string $domain
     */
    public function __construct(string $key, string $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
    }

    protected function getClient(): Mailgun
    {
        if (is_null($this->client)) {
            $this->client = Mailgun::create($this->key);
        }
        return $this->client;
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

        if ($email->getRecipientName()) {
            $mailParams['to'] = $email->getRecipientName() . ' <' . $email->getRecipientEmail() . '>';
        } else {
            $mailParams['to'] = $email->getRecipientEmail();
        }
        $mailParams['subject'] = $email->getSubject();
        $mailParams['text'] = $email->getTextContent();
        $mailParams['html'] = $email->getHtmlContent();

        if (!empty($email->getAttachments())) {
            $mailParams['attachment'] = $this->prepareAttachments($email->getAttachments());
        }

        try {
            $this->getClient()->messages()->send($this->domain, $mailParams);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through Mailgun", 0, $e);
        }
    }

    /**
     * @param File[] $files
     * @return array
     */
    protected function prepareAttachments(array $files): array
    {
        $attachments = [];

        foreach ($files as $attachment) {
            $attachments[] = ['filePath' => $attachment->getRealPath()];
        }

        return $attachments;
    }
}
