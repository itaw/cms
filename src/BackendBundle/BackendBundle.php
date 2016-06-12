<?php

namespace BackendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class BackendBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
