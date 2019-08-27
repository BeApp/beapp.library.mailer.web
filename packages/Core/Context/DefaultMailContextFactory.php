<?php

namespace Beapp\Email\Core\Context;

use Beapp\Email\Core\Translation\TranslationInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class DefaultMailContextFactory implements MailContextFactory
{

    /** @var RouterInterface $router */
    private $router;

    /** @var TranslationInterface $translator */
    private $translator;

    /** @var EngineInterface $templating */
    private $templating;

    /**
     * MailerService constructor.
     * @param RouterInterface $router
     * @param TranslationInterface $translator
     * @param EngineInterface $templating
     */
    public function __construct(
        RouterInterface $router,
        TranslationInterface $translator,
        EngineInterface $templating
    )
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->templating = $templating;
    }

    public function buildContext(): MailContext
    {
        return new MailContext($this->router, $this->translator, $this->templating);
    }

}
