<?php

namespace Beapp\Email\Core\Context;

interface MailContextFactory
{

    public function buildContext(): MailContext;

}
