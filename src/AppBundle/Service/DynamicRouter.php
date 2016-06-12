<?php

namespace AppBundle\Service;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class DynamicRouter extends Loader
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        //load static routes
        $importedRoutes = $this->import('@AppBundle/Resources/config/static-routing.yml', 'yaml');
        $collection->addCollection($importedRoutes);

        //load dynamic routes from database
        $pages = $this->doctrine->getRepository('DataBundle:Page')->findBy(
            ['active' => true],
            ['ordering' => 'asc']
        );

        $importedRoutes = new RouteCollection();

        //add default page if no pages are defined
        if (count($pages) == 0) {
            $route = new Route('/', [
                '_controller' => 'AppBundle:Page:defaultIndex',
            ]);

            $importedRoutes->add('index', $route);
        }

        foreach ($pages as $page) {
            $route = new Route('/'.$page->getSlug(), [
                '_controller' => 'AppBundle:Page:page',
            ]);

            //todo: make slug conversion safe
            if ($page->getIsIndex()) {
                $name = 'index';
            } else {
                $name = str_replace('/', '', strtolower(trim($page->getTitle())));
            }
            $importedRoutes->add($name, $route);

            //generate blog post url for tag
            if ($page->getIsBlogTag() && !is_null($page->getTag())) {
                $route = new Route('/'.trim($page->getSlug()).'/post/{slug}', [
                    '_controller' => 'AppBundle:Page:post',
                ]);

                $route->addOptions(['post_from' => 'tag']);

                $name = str_replace('/', '', strtolower(trim($page->getTitle())).'_post');
                $importedRoutes->add($name, $route);
            }

            //generate blog post url for blog
            if ($page->getIsBlog() && !is_null($page->getBlog())) {
                $route = new Route('/'.trim($page->getSlug()).'/post/{slug}', [
                    '_controller' => 'AppBundle:Page:post',
                ]);

                $route->addOptions(['post_from' => 'blog']);

                $name = str_replace('/', '', strtolower(trim($page->getTitle())).'_post');
                $importedRoutes->add($name, $route);
            }
        }

        $collection->addCollection($importedRoutes);

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return $type === 'dynamic';
    }
}
