<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Blog;
use DataBundle\Entity\BlogPost;
use DataBundle\Entity\Tag;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class BlogController extends Controller
{
    private function generateSlug($str)
    {
        // replace non letter or digits by -
        $str = preg_replace('~[^\\pL\d]+~u', '-', $str);
        // trim
        $str = trim($str, '-');
        // transliterate
        $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
        // lowercase
        $str = strtolower($str);
        // remove unwanted characters
        $str = preg_replace('~[^-\w]+~', '', $str);
        if (empty($str)) {
            return 'n-a';
        }

        return $str;
    }

    public function collectionAction(Request $request)
    {
        $blogs = $this->getDoctrine()->getRepository('DataBundle:Blog')->findAll();

        return $this->render('BackendBundle:Blog:collection.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    public function createAction(Request $request)
    {
        if ($request->get('sent', 0) == 1) {
            $blog = new Blog();
            $blog
                ->setTitle($request->get('title'))
                ->setSlug($this->generateSlug($request->get('title')))
            ;

            //validate

            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('backend_blogs');
        }

        return $this->render('BackendBundle:Blog:create.html.twig');
    }

    public function updateAction(Request $request, $blogId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        if ($request->get('sent', 0) == 1) {
            $blog
                ->setTitle($request->get('title'))
                ->setSlug($this->generateSlug($request->get('title')))
            ;

            //validate

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('backend_blogs');
        }

        return $this->render('BackendBundle:Blog:update.html.twig', [
            'blog' => $blog,
        ]);
    }

    public function deleteAction(Request $request, $blogId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        if (count($blog->getPosts()) == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('backend_blogs');
    }

    public function postsCollectionAction(Request $request, $blogId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        if($request->get('tag', '') != '') {
            $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')
                ->findByBlogAndTagSlugOrderedPaginated(
                    $blog,
                    $request->get('tag', ''),
                    'desc',
                    $this->get('knp_paginator'),
                    $request->query->getInt('page', 1)
                )
            ;
        } else {
            $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')
                ->findByBlogOrderedPaginated(
                    $blog,
                    'desc',
                    $this->get('knp_paginator'),
                    $request->query->getInt('page', 1)
                )
            ;
        }

        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();

        return $this->render('BackendBundle:Blog:posts_collection.html.twig', [
            'blog' => $blog,
            'posts' => $posts,
            'tags' => $tags,
        ]);
    }

    public function postsSearchAction(Request $request, $blogId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        $query = $request->get('q');

        $posts = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findWithKeyword($query);

        return $this->render('BackendBundle:Blog:posts_search.html.twig', [
            'blog' => $blog,
            'posts' => $posts,
        ]);
    }

    public function postsCreateAction(Request $request, $blogId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();

        if ($request->get('sent', 0) == 1) {
            $post = new BlogPost();
            $post
                ->setTitle($request->get('title'))
                ->setSlug($this->generateSlug($request->get('title')))
                ->setContent($request->get('text'))
                ->setExcerpt($request->get('excerpt'))
                ->setPublishDate(new \DateTime('now'))
                ->setMainImageUrl($request->get('main_image_url'))
                ->setIsMarkdown(false)
                ->setBlog($blog)
                ->setAuthor($this->getUser())
                ->setPublished(($request->get('published') == 'true') ? true : false)
            ;

            $em = $this->getDoctrine()->getManager();

            //handle tags
            $_tags = json_decode($request->get('tags'));
            if (!is_null($_tags)) {
                foreach ($_tags as $_tag) {
                    $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneByTitle($_tag);

                    //if tag is unknown create new
                    if (!$tag) {
                        $tag = new Tag();
                        $tag
                            ->setTitle($_tag)
                            ->setSlug($this->generateSlug($_tag))
                        ;

                        //validation

                        $em->persist($tag);
                    }

                    $post->addTag($tag);
                }
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('backend_blogs_posts', [
                'blogId' => $blog->getId(),
            ]);
        }

        return $this->render('BackendBundle:Blog:posts_create.html.twig', [
            'blog' => $blog,
            'tags' => $tags,
        ]);
    }

    public function postsUpdateAction(Request $request, $blogId, $postId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        $post = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findOneById($postId);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();

        if ($request->get('sent', 0) == 1) {
            $post
                ->setTitle($request->get('title'))
                ->setSlug($this->generateSlug($request->get('title')))
                ->setContent($request->get('text'))
                ->setExcerpt($request->get('excerpt'))
                ->setMainImageUrl($request->get('main_image_url'))
                ->setIsMarkdown(false)
                ->setPublished(($request->get('published') == 'true') ? true : false)
                ->setPublishDate(new \DateTime($request->get('publish_date')))
            ;

            $em = $this->getDoctrine()->getManager();

            //handle tags
            $post->removeTags();
            $_tags = json_decode($request->get('tags'));
            if (!is_null($_tags)) {
                foreach ($_tags as $_tag) {
                    $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneByTitle($_tag);

                    //if tag is unknown create new
                    if (!$tag) {
                        $tag = new Tag();
                        $tag
                            ->setTitle($_tag)
                            ->setSlug($this->generateSlug($_tag))
                        ;

                        //validation

                        $em->persist($tag);
                    }

                    $post->addTag($tag);
                }
            }

            $em->flush();

            return $this->redirectToRoute('backend_blogs_posts', [
                'blogId' => $blog->getId(),
            ]);
        }

        return $this->render('BackendBundle:Blog:posts_update.html.twig', [
            'blog' => $blog,
            'post' => $post,
            'tags' => $tags,
        ]);
    }

    public function postsDeleteAction(Request $request, $blogId, $postId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        $post = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findOneById($postId);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('backend_blogs_posts', [
            'blogId' => $blog->getId(),
        ]);
    }

    public function postsTogglePublishedAction(Request $request, $blogId, $postId)
    {
        $blog = $this->getDoctrine()->getRepository('DataBundle:Blog')->findOneById($blogId);

        if (!$blog) {
            throw $this->createNotFoundException();
        }

        $post = $this->getDoctrine()->getRepository('DataBundle:BlogPost')->findOneById($postId);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        $post->setPublished(!$post->getPublished());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('backend_blogs_posts', [
            'blogId' => $blog->getId(),
        ]);
    }
}
