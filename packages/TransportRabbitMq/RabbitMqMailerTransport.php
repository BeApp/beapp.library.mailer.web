<?php

namespace Beapp\Email\Transport\Rabbitmq;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class RabbitMqMailerTransport implements MailerTransport
{
    /** @var ProducerInterface */
    private $producer;
    /** @var string */
    private $routingKey;

    /**
     * @param ProducerInterface $producer
     * @param string $routingKey
     */
    public function __construct(ProducerInterface $producer, string $routingKey = '')
    {
        $this->producer = $producer;
        $this->routingKey = $routingKey;
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
            $this->producer->publish($this->preparePayload($email), $this->routingKey);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through RabbitMq", 0, $e);
        }
    }

    protected function preparePayload(Mail $email): string
    {
        return json_encode($email);
    }
}
