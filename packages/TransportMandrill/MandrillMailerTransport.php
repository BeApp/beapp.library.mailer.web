<?php

namespace Beapp\Email\Transport\Mandrill;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use Symfony\Component\HttpFoundation\File\File;
use MailchimpTransactional\ApiClient as Mandrill;
use Exception;

class MandrillMailerTransport implements MailerTransport
{
    /** @var Mandrill|null */
    private $client;
    /** @var string */
    private $apiKey;
    /** @deprecated @var string|null */
    private $ipPool;

    /**
     * @param string $apiKey
     * @param string|null $ipPool
     */
    public function __construct(string $apiKey, ?string $ipPool)
    {
        $this->apiKey = $apiKey;
        $this->ipPool = $ipPool;
    }

    /**
     * @throws MailerException
     */
    protected function getClient(): ?Mandrill
    {
        if (is_null($this->client)) {
            try {
                $this->client = new Mandrill();
            } catch (Exception $e) {
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
            if(!is_null($email->getTemplateKey())){
                $ret = $this->getClient()->templates->info($email->getTemplateKey());
                $html = $ret['publish_code'];
                $text = $ret['publish_text'];
                foreach ($email->getData() as $key => $val) {
                    $html = str_replace($key, $val, $html);
                    $text = str_replace($key, $val, $text);
                }
                $toEmail = $ret['toEmail'];
                $subject = $ret['subject'];
                $fromEmail = $ret['from_email'];
                $fromName = $ret['from_name'];
            } else{
                $html = $email->getHtmlContent();
                $text = $email->getTextContent();
                $toEmail = $email->getRecipientEmail();
                $subject = $email->getSubject();
                $fromEmail = $email->getSenderEmail();
                $fromName = $email->getSenderName();
            }

            if(!is_null($email->getBulkRecipients())){
                $recipients = [];
                foreach($email->getBulkRecipients() as $item)
                {
                    $recipients [] = [
                        'email' => $item,
                        'type' => 'to',
                    ];
                }
            }elseif(!is_null($toEmail)){
                $recipients =  [['email' => $toEmail, 'type' => 'to']];
            }
            else{
                throw new Exception("Missing recipient", 0);
            }

            $message = array(
                'html' => $html,
                'text' => $text,
                'subject' => $subject,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'to' => $recipients,
                'headers' => ['Reply-To' => $fromEmail],
            );

            if (!empty($email->getAttachments())) {
                $message['attachments'] = $this->prepareAttachments($email->getAttachments());
            }
            $this->getClient()->setApiKey($this->apiKey);
            $this->getClient()->messages->send([
                "message" => $message
            ]);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through Mandrill", 0, $e);
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
