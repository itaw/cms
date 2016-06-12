<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Setting;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class SettingController extends Controller
{
    public function collectionAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('DataBundle:Setting')->findAll();

        return $this->render('BackendBundle:Setting:collection.html.twig', [
            'settings' => $settings,
        ]);
    }

    public function createAction(Request $request)
    {
        if ($request->get('sent', 0) == 1) {
            $setting = new Setting();
            $setting
                ->setSettingKey($request->get('setting_key'))
                ->setSettingValue($request->get('setting_value'))
                ->setIsDeep(($request->get('deep') == 'true') ? true : false)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($setting);
            $em->flush();

            return $this->redirectToRoute('backend_settings');
        }

        return $this->render('BackendBundle:Setting:create.html.twig');
    }

    public function updateAction(Request $request, $settingId)
    {
        $setting = $this->getDoctrine()->getRepository('DataBundle:Setting')->findOneById($settingId);

        if (!$setting) {
            return $this->createNotFoundException();
        }

        if ($request->get('sent', 0) == 1) {
            $setting
                ->setSettingKey($request->get('setting_key'))
                ->setSettingValue($request->get('setting_value'))
                ->setIsDeep(($request->get('deep') == 'true') ? true : false)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('backend_settings');
        }

        return $this->render('BackendBundle:Setting:update.html.twig', [
            'setting' => $setting,
        ]);
    }

    public function deleteAction(Request $request, $settingId)
    {
        $setting = $this->getDoctrine()->getRepository('DataBundle:Setting')->findOneById($settingId);

        if (!$setting) {
            return $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($setting);
        $em->flush();

        return $this->redirectToRoute('backend_settings');
    }
}
