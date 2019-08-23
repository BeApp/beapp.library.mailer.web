<?php

namespace Beapp\Email\Core\Template;

use Beapp\Email\Core\Mail;
use Beapp\Email\Core\Translation\TranslationInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

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
     * @param TranslationInterface $translator
     * @param EngineInterface $templating
     * @return Mail
     */
    public function build(RouterInterface $router, TranslationInterface $translator, EngineInterface $templating): Mail;
}
