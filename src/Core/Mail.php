<?php

namespace Beapp\Email\Core;

class Mail implements \JsonSerializable
{
    /** @var string */
    private $subject;
    /** @var string|null */
    private $templateKey;
    /** @var string|null */
    private $textContent;
    /** @var string|null */
    private $htmlContent;
    /** @var string */
    private $recipientEmail;
    /** @var string|null */
    private $recipientName;
    /** @var string|null */
    private $replyTo;
    /** @var string|null */
    private $senderEmail;
    /** @var string|null */
    private $senderName;
    /** @var string[] */
    private $cc = [];
    private $data = [];

    /**
     * Message constructor.
     *
     * @param string $recipientEmail The first recipient email address
     * @param string|null $recipientName The first recipient name
     * @param string $subject The message subject
     * @param string|null $templateKey
     * @param string|null $textContent
     * @param string|null $htmlContent
     * @param string|null $replyTo The email address to use for the Reply-to header
     */
    public final function __construct(
        string $recipientEmail,
        $recipientName,
        string $subject,
        ?string $templateKey,
        ?string $textContent,
        ?string $htmlContent,
        $replyTo = null
    ) {
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName;
        $this->subject = $subject;
        $this->templateKey = $templateKey;
        $this->textContent = $textContent;
        $this->htmlContent = $htmlContent;
        $this->replyTo = $replyTo;
    }

    public static function jsonDeserialize(array $data): self
    {
        $mail = new self(
            $data['toEmail'],
            $data['toName'],
            $data['subject'],
            $data['templateKey'],
            $data['text'],
            $data['html'],
            $data['replyTo']
        );
        if ($senderEmail = $data['senderEmail']) {
            $mail->setSenderEmail($senderEmail);
        }
        if ($senderName = $data['senderName']) {
            $mail->setSenderName($senderName);
        }
        if ($cc = $data['cc']) {
            $mail->setCc($cc);
        }
        if ($data = $data['data']) {
            $mail->setData($data);
        }
        return $mail;
    }

    public function jsonSerialize(): array
    {
        return [
            'toEmail' => $this->getRecipientEmail(),
            'toName' => $this->getRecipientName(),
            'subject' => $this->getSubject(),
            'templateKey' => $this->getTemplateKey(),
            'text' => $this->getTextContent(),
            'html' => $this->getHtmlContent(),
            'replyTo' => $this->getReplyTo(),
            'senderEmail' => $this->getSenderEmail(),
            'senderName' => $this->getSenderName(),
            'cc' => $this->getReplyTo(),
            'data' => $this->getData(),
        ];
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return null|string
     */
    public function getTemplateKey(): ?string
    {
        return $this->templateKey;
    }

    /**
     * @param null|string $templateKey
     */
    public function setTemplateKey(?string $templateKey): void
    {
        $this->templateKey = $templateKey;
    }

    /**
     * @return string
     */
    public function getTextContent(): string
    {
        return $this->textContent;
    }

    /**
     * @param string $textContent
     */
    public function setTextContent(string $textContent)
    {
        $this->textContent = $textContent;
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    /**
     * @param string $htmlContent
     */
    public function setHtmlContent(string $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return string
     */
    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    /**
     * @param string $recipientEmail
     */
    public function setRecipientEmail(string $recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @return string|null
     */
    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    /**
     * @param string|null $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return string|null
     */
    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    /**
     * @param string|null $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return string|null
     */
    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    /**
     * @param string|null $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return string|null
     */
    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    /**
     * @param string|null $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @param string[] $cc
     */
    public function setCc(array $cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

}

