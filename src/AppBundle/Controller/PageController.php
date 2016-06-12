<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class PageController extends Controller
{
    /**
     * Page resolver Action.
     */
    public function pageAction(Request $request)
    {
        $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneByTitle(
            $request->get('_route')
        );

        if (!$page) {
            $page = $this->getDoctrine()->getRepository('DataBundle:Page')->findOneByIsIndex(true);

            if (!$page) {
                throw $this->createNotFoundException('This Page does not exist!');
            }
        }

        //blog
        if ($page->getIsBlog() && !is_null($page->getBlog())) {
            $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')
                ->findByBlogOrderedPaginated(
                    $page->getBlog(),
                    'desc',
                    $this->get('knp_paginator'),
                    $request->query->getInt('page', 1)
                )
            ;

            return $this->render('blog.html.twig', [
                'page' => $page,
                'posts' => $posts,
            ]);
        }

        //tag
        if ($page->getIsBlogTag() && !is_null($page->getTag())) {
            $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')
                ->findByTagOrderedPaginated(
                    $page->getTag(),
                    'desc',
                    $this->get('knp_paginator'),
                    $request->query->getInt('page', 1)
                )
            ;

            return $this->render('tag.html.twig', [
                'page' => $page,
                'posts' => $posts,
            ]);
        }

        //page
        return $this->render('page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * BlogPost Action.
     */
    public function postAction(Request $request, $slug)
    {
        $post = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findOneBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException('This Post does not exist!');
        }

        return $this->render('post.html.twig', [
            'post' => $post,
        ]);
    }

    public function defaultIndexAction()
    {
        return $this->render('BackendBundle::default_index.html.twig');
    }

    public function searchAction(Request $request)
    {
        $query = $request->get('q');

        $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findWithKeyword($query);
        $pages = $this->getDoctrine()->getRepository('DataBundle:Page')->findWithKeyword($query);

        return $this->render('search.html.twig', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }
}
