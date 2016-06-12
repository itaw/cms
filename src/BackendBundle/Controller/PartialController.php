<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Partial;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class PartialController extends Controller
{
    private function generateSlug($str)
    {
        // replace non letter or digits by -
        $str = preg_replace('~[^\\pL\d]+~u', '_', $str);
        // trim
        $str = trim($str, '_');
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
        $partials = $this->getDoctrine()->getRepository('DataBundle:Partial')->findAll();

        return $this->render('BackendBundle:Partial:collection.html.twig', [
            'partials' => $partials,
        ]);
    }

    public function createAction(Request $request)
    {
        if ($request->get('sent', 0) == 1) {
            $partial = new Partial();
            $partial
                ->setTitle($this->generateSlug($request->get('title')))
                ->setContent($request->get('content'))
                ->setActive(($request->get('active') == 'true') ? true : false)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($partial);
            $em->flush();

            return $this->redirectToRoute('backend_partials');
        }

        return $this->render('BackendBundle:Partial:create.html.twig');
    }

    public function updateAction(Request $request, $partialId)
    {
        $partial = $this->getDoctrine()->getRepository('DataBundle:Partial')->findOneById($partialId);

        if (!$partial) {
            return $this->createNotFoundException();
        }

        if ($request->get('sent', 0) == 1) {
            $partial
                ->setTitle($this->generateSlug($request->get('title')))
                ->setContent($request->get('content'))
                ->setActive(($request->get('active') == 'true') ? true : false)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('backend_partials');
        }

        return $this->render('BackendBundle:Partial:update.html.twig', [
            'partial' => $partial,
        ]);
    }

    public function deleteAction(Request $request, $partialId)
    {
        $partial = $this->getDoctrine()->getRepository('DataBundle:Partial')->findOneById($partialId);

        if (!$partial) {
            return $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($partial);
        $em->flush();

        return $this->redirectToRoute('backend_partials');
    }

    public function toggleActiveAction(Request $request, $partialId)
    {
        $partial = $this->getDoctrine()->getRepository('DataBundle:Partial')->findOneById($partialId);

        if (!$partial) {
            return $this->createNotFoundException();
        }

        $partial->setActive(!$partial->getActive());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('backend_partials');
    }
}
