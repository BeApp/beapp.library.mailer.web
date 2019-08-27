<?php

namespace Beapp\Email\Core\Translation;

interface TranslationInterface
{

    public function trans($id, array $parameters = array(), $domain = null, $locale = null);

    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null);
    
    public function setLocale($locale);

    public function getLocale();

}
