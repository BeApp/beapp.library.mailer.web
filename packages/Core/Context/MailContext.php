<?php

namespace Beapp\Email\Core\Context;

use Beapp\Email\Core\Translation\TranslationInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class MailContext
{

    /** @var RouterInterface $router */
    private $router;

    /** @var TranslationInterface $translator */
    private $translator;

    /** @var EngineInterface $templating */
    private $templating;

    /**
     * @param RouterInterface $router
     * @param TranslationInterface $translator
     * @param EngineInterface $templating
     */
    public function __construct(RouterInterface $router, TranslationInterface $translator, EngineInterface $templating)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->templating = $templating;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating(): EngineInterface
    {
        return $this->templating;
    }

    /**
     * @return TranslationInterface
     */
    public function getTranslator(): TranslationInterface
    {
        return $this->translator;
    }

}
