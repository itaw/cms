<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Page;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class PageController extends Controller
{
    private function clearRoutingCache()
    {
        $files = glob($this->container->getParameter('kernel.cache_dir').'/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    public function collectionAction(Request $request)
    {
        $pages = $this->getDoctrine()->getRepository('DataBundle:Page')
            ->findBy([], ['ordering' => 'asc'])
        ;

        return $this->render('BackendBundle:Page:collection.html.twig', [
            'pages' => $pages,
        ]);
    }

    public function createAction(Request $request)
    {
        $blogs = $this->getDoctrine()->getRepository('DataBundle:Blog')->findAll();
        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();
        $pages = $this->getDoctrine()->getRepository('DataBundle:Page')->findBy([], ['ordering' => 'asc']);

        if ($request->get('sent', 0) == 1) {
            $page = new Page();
            $page
                ->setTitle($request->get('title'))
                ->setSlug($request->get('slug'))
                ->setContent($request->get('content'))
                ->setMetaTitle($request->get('meta_title'))
                ->setMetaDescription($request->get('meta_description'))
                ->setActive(($request->get('active') == 'true') ? true : false)
                ->setHidden(($request->get('hidden') == 'true') ? true : false)
                ->setEnableTwig(true)
                ->setOrdering(count($pages));

            //set parent
            if ($request->get('parent', 0) != 0) {
                $parent = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($request->get('parent'));

                if (!$parent) {
                    //error
                }

                $page->setParent($parent);

                //reorder following entries
                $upper = $this->getDoctrine()->getRepository('DataBundle:Page')->findBy(
                    ['ordering' => '>= '.$parent->getOrdering() + 1]
                );

                foreach ($upper as $u) {
                    $u->setOrdering($u->getOrdering() + 1);
                }

                $page->setOrdering($parent->getOrdering());
                $page->setSlug($parent->getSlug().'/'.$page->getSlug());
            }

            //set selected blog
            if ($request->get('blog', 0) != 0) {
                $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($request->get('blog', 0));

                if (!$blog) {
                    //error
                }

                $page->setBlog($blog);
                $page->setIsBlog(true);
                $page->setIsBlogTag(false);
            }

            //set selected tag
            if ($request->get('tag', 0) != 0) {
                $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneById($request->get('tag', 0));

                if (!$tag) {
                    //error
                }

                $page->setTag($tag);
                $page->setIsBlogTag(true);
                $page->setIsBlog(false);
            }

            //validate

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            //new page gets inserted on 0
            //restore order
            $this->get('app.backend.page')->restoreOrder();

            //clear cache
            $this->clearRoutingCache();

            return $this->redirectToRoute('backend_pages');
        }

        return $this->render('BackendBundle:Page:create.html.twig', [
            'blogs' => $blogs,
            'tags' => $tags,
            'all_pages' => $pages,
        ]);
    }

    public function updateAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $blogs = $this->getDoctrine()->getRepository('DataBundle:Blog')->findAll();
        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();
        $pages = $this->getDoctrine()->getRepository('DataBundle:Page')->findBy([], ['ordering' => 'asc']);

        if ($request->get('sent', 0) == 1) {
            $page
                ->setTitle($request->get('title'))
                ->setSlug($request->get('slug'))
                ->setContent($request->get('content'))
                ->setMetaTitle($request->get('meta_title'))
                ->setMetaDescription($request->get('meta_description'))
                ->setActive(($request->get('active') == 'true') ? true : false)
                ->setHidden(($request->get('hidden') == 'true') ? true : false)
            ;

            //update selected blog
            if ($request->get('blog', 0) != 0) {
                $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($request->get('blog', 0));

                if (!$blog) {
                    //error
                }

                $page->setBlog($blog);
                $page->setIsBlog(true);
            } else {
                $page->setBlog(null);
                $page->setIsBlog(false);
            }

            //update selected tag
            if ($request->get('tag', 0) != 0) {
                $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneById($request->get('tag', 0));

                if (!$tag) {
                    //error
                }

                $page->setTag($tag);
                $page->setIsBlogTag(true);
            } else {
                $page->setTag(null);
                $page->setIsBlogTag(false);
            }

            //validate

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //clear cache
            $this->clearRoutingCache();

            return $this->redirectToRoute('backend_pages');
        }

        return $this->render('BackendBundle:Page:update.html.twig', [
            'page' => $page,
            'blogs' => $blogs,
            'tags' => $tags,
            'all_pages' => $pages,
        ]);
    }

    public function deleteAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        //restore ordering after deletion
        $this->get('app.backend.page')->restoreOrder();

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }

    public function toggleActiveAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $page->setActive(!$page->getActive());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }

    public function toggleHiddenAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $page->setHidden(!$page->getHidden());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }

    public function toggleIndexAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        //get current index page and remove setting
        $index = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneByIsIndex(true);

        $em = $this->getDoctrine()->getManager();

        if (!(!$index)) {
            $index->setIsIndex(false);
            $index->setSlug(strtolower($index->getTitle()));

            $em->flush();
        }

        $page->setIsIndex(true);
        $page->setSlug('');

        $em->flush();

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }

    public function moveUpAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        //only move up if not first position
        //and if page has no parent
        if ($page->getOrdering() != 0 && is_null($page->getParent())) {
            $pre = $this->getDoctrine()
                ->getRepository('DataBundle:Page')
                ->findOneByOrdering($page->getOrdering() - 1)
            ;

            $newOrder = $pre->getOrdering();
            $pre->setOrdering($page->getOrdering());
            $page->setOrdering($newOrder);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }

    public function moveDownAction(Request $request, $pageId)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneById($pageId);
        $totalPages = count($this->getDoctrine()->getRepository('DataBundle:Page')->findAll());

        if (!$page) {
            throw $this->createNotFoundException();
        }

        //only move down if not last page
        //and if page has no parent
        if ($page->getOrdering() != $totalPages - 1 && is_null($page->getParent())) {
            $post = $this->getDoctrine()
                ->getRepository('DataBundle:Page')
                ->findOneByOrdering($page->getOrdering() + 1)
            ;

            $newOrder = $post->getOrdering();
            $post->setOrdering($page->getOrdering());
            $page->setOrdering($newOrder);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        //clear cache
        $this->clearRoutingCache();

        return $this->redirectToRoute('backend_pages');
    }
}
