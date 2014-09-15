<?php

namespace sb\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class sbUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
