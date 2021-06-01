<?php

namespace Beapp\Email\Transport\Mandrill;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use Symfony\Component\HttpFoundation\File\File;

class MandrillMailerTransport implements MailerTransport
{
    /** @var \Mandrill|null */
    private $client;
    /** @var string */
    private $apiKey;
    /** @var string */
    private $ipPool;

    /**
     * @param string $apiKey
     * @param string $ipPool
     */
    public function __construct(string $apiKey, string $ipPool)
    {
        $this->apiKey = $apiKey;
        $this->ipPool = $ipPool;
    }

    /**
     * @throws MailerException
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            try {
                $this->client = new \Mandrill($this->apiKey);
            } catch (\Mandrill_Error $e) {
                throw new MailerException("Couldn't initialize Mandrill transport", 0, $e);
            }
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
        try {
            $ret = $this->getClient()->templates->info($email->getTemplateKey());

            $html = $ret['publish_code'];
            $text = $ret['publish_text'];
            foreach ($email->getData() as $key => $val) {
                $html = str_replace($key, $val, $html);
                $text = str_replace($key, $val, $text);
            }

            $message = array(
                'html' => $html,
                'text' => $text,
                'subject' => !empty($email->getSubject()) ? $email->getSubject() : $ret['subject'],
                'from_email' => !empty($email->getSenderEmail()) ? $email->getSenderEmail() : $ret['from_email'],
                'from_name' => !empty($email->getSenderName()) ? $email->getSenderName() : $ret['from_name'],
                'to' => [
                    ['email' => $email->getRecipientEmail(), 'type' => 'to']
                ],
                'headers' => ['Reply-To' => !empty($email->getReplyTo()) ? $email->getReplyTo() : $ret['from_email']],
            );

            if (!empty($email->getAttachments())) {
                $message['attachments'] = $this->prepareAttachments($email->getAttachments());
            }

            /** @noinspection PhpParamsInspection */
            $this->getClient()->messages->send($message, true, $this->ipPool);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through Mandrill", 0, $e);
        }
    }

    /**
     * Delivers bulk email
     *
     * @throws MailerException
     */
    public function sendBulkEmail(Mail $email): void
    {
        $recipients = [];
        foreach($email->getData() as $item)
        {
            $recipients [] = [
                'email' => $item,
                'type' => 'to',
            ];
        }
        try {
            $html = $email->getHtmlContent();
            $text = $email->getTextContent();
            $message = [
                'html' => $html,
                'text' => $text,
                'subject' => $email->getSubject(),
                'from_email' => $email->getSenderEmail(),
                'from_name' => $email->getSenderName(),
                'to' => $recipients,
                'headers' => ['Reply-To' => "beapp.support@blynd.com"],
            ];

            $this->getClient()->setApiKey($this->apiKey);
            $this->getClient()->messages->send([
                "message" => $message
            ]);
        } catch (Exception $e) {
            throw new MailerException("Couldn't send bulk mail through Mandrill", 0, $e);
        }
    }

    /**
     * @param File[] $attachments
     * @return array
     */
    protected function prepareAttachments(array $attachments): array
    {
        return array_map(function (File $attachment) {
            return [
                'type' => $attachment->getMimeType(),
                'name' => $attachment->getFilename(),
                'content' => file_get_contents($attachment->getRealPath()),
            ];
        }, $attachments);
    }
}
