<?php

namespace Rareloop\Lumberjack\Email\Facades;

use Blast\Facades\AbstractFacade;

class Email extends AbstractFacade
{
    protected static function accessor()
    {
        return 'email';
    }
}
