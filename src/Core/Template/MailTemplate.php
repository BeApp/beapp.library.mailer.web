<?php

namespace Beapp\Email\Core\Template;

use Beapp\Email\Core\Mail;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface MailTemplate defines the email to send
 * @package Beapp\Email\Template
 */
interface MailTemplate
{
    /**
     * Call by {@link MailService} to build a {@link Mail} instance.
     *
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param EngineInterface $templating
     * @return Mail
     */
    public function build(RouterInterface $router, TranslatorInterface $translator, EngineInterface $templating): Mail;
}
