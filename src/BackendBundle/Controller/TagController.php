<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Tag;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class TagController extends Controller
{
    public function collectionAction(Request $request)
    {
        $tags = $this->getDoctrine()->getRepository('DataBundle:Tag')->findAll();

        return $this->render('BackendBundle:Tag:collection.html.twig', [
            'tags' => $tags,
        ]);
    }

    public function updateAction(Request $request, $tagId)
    {
        $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneById($tagId);

        if (!$tag) {
            return $this->createNotFoundException();
        }

        if ($request->get('sent', 0) == 1) {
            $tag
                ->setTitle($request->get('title'))
            ;

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('backend_tags');
        }

        return $this->render('BackendBundle:Tag:update.html.twig', [
            'tag' => $tag,
        ]);
    }

    public function deleteAction(Request $request, $tagId)
    {
        $tag = $this->getDoctrine()->getRepository('DataBundle:Tag')->findOneById($tagId);

        if (!$tag) {
            return $this->createNotFoundException();
        }

        if (count($tag->getPosts()) == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();
        }

        return $this->redirectToRoute('backend_tags');
    }
}
