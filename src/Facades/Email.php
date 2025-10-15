<?php

namespace Rareloop\Lumberjack\Email\Facades;

use Rareloop\Lumberjack\Facades\AbstractFacade;

class Email extends AbstractFacade
{
    protected static function accessor()
    {
        return 'email';
    }
}
