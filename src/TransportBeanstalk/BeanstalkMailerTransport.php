<?php

namespace Beapp\Email\Transport;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\MailerException;
use Beapp\Email\Core\Transport\MailerTransport;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;

class BeanstalkMailerTransport implements MailerTransport
{
    /** @var Pheanstalk */
    private $client;
    /** @var string */
    private $beanstakdHost;
    /** @var string */
    private $tube;
    /** @var int */
    private $priority;
    /** @var int */
    private $delay;
    /** @var int */
    private $ttr;

    /**
     * @param string $beanstalkHost
     * @param string $tube
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     */
    public function __construct(
        string $beanstalkHost,
        string $tube,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    ) {
        $this->beanstakdHost = $beanstalkHost;
        $this->tube = $tube;
        $this->priority = $priority;
        $this->delay = $delay;
        $this->ttr = $ttr;
    }

    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Pheanstalk($this->beanstakdHost);
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
            $this->getClient()
                ->useTube($this->tube)
                ->put($this->preparePayload($email), $this->priority, $this->delay, $this->ttr);
        } catch (\Exception $e) {
            throw new MailerException("Couldn't send mail through Beanstalk", 0, $e);
        }
    }

    protected function preparePayload(Mail $email): string
    {
        return json_encode($email);
    }
}
