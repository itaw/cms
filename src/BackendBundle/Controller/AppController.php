<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class AppController extends Controller
{
    public function dashboardAction(Request $request)
    {
        $pagesTotal = count($this->getDoctrine()->getRepository('DataBundle:Page')->findAll());
        $pagesActive = count($this->getDoctrine()->getRepository('DataBundle:Page')->findBy(['active' => true]));

        return $this->render('BackendBundle::dashboard.html.twig', [
            'pages_total' => $pagesTotal,
            'pages_active' => $pagesActive,
        ]);
    }
}
