<?php

namespace Beapp\Email\Core\Template;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\Context\MailContext;

/**
 * Interface MailTemplate defines the email to send
 * @package Beapp\Email\Template
 */
interface MailTemplate
{
    /**
     * Call by {@link MailService} to build a {@link Mail} instance.
     *
     * @param MailContext $context
     * @return Mail
     */
    public function build(MailContext $context): Mail;

}
