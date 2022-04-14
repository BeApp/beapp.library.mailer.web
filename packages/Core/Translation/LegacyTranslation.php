<?php

namespace Beapp\Email\Core\Translation;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @deprecated
 */
class LegacyTranslation implements TranslationInterface
{
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }

    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }

    public function getLocale()
    {
        return $this->translator->getLocale();
    }
}
