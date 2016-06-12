<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Twig\Extension\RoutingExtension;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class CustomRoutingExtension extends RoutingExtension
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function getFunctions()
    {
        $fn = parent::getFunctions();

        $fn[] = new \Twig_SimpleFunction('path_safe', array($this, 'getPathSafe'));

        return $fn;
    }

    public function getPathSafe($name, $parameters = array(), $relative = false)
    {
        try {
            $url = $this->getPath($name, $parameters, $relative);
        } catch (\Exception $exc) {
            return '#';
        }

        return $url;
    }
}
