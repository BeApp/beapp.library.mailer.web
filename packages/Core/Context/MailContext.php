<?php

namespace Beapp\Email\Core\Context;

use Beapp\Email\Core\Translation\TranslationInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class MailContext
{

    /** @var RouterInterface $router */
    private $router;

    /** @var TranslationInterface $translator */
    private $translator;

    /** @var Environment $templating */
    private $templating;

    /**
     * @param RouterInterface $router
     * @param TranslationInterface $translator
     * @param Environment $templating
     */
    public function __construct(RouterInterface $router, TranslationInterface $translator, Environment $templating)
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
     * @return Environment
     */
    public function getTemplating(): Environment
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
