<?php

namespace AppBundle\Twig;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class AppExtension extends \Twig_Extension
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_menu', array($this, 'renderMenu'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new \Twig_SimpleFunction('get_setting', array($this, 'getSetting')),
            new \Twig_SimpleFunction('render_recent_posts', array($this, 'renderRecentPosts'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new \Twig_SimpleFunction('render_twig', array($this, 'renderTwig'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new \Twig_SimpleFunction('render_partial', array($this, 'renderPartial'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    public function renderMenu(\Twig_Environment $twig, $currentPage = null, $mobile = false)
    {
        $pages = $this->doctrine->getRepository('DataBundle:Page')->findBy(
            ['active' => true, 'hidden' => false], ['ordering' => 'asc']
        );

        return $twig->render('_menu.html.twig', [
            'pages' => $pages,
            'currentPage' => $currentPage,
            'mobile' => $mobile,
        ]);
    }

    public function getSetting($key)
    {
        $setting = $this->doctrine->getRepository('DataBundle:Setting')->findOneBySettingKey($key);

        if (!$setting) {
            return;
        }

        if ($setting->getIsDeep()) {
            return json_decode($setting->getSettingValue());
        } else {
            return $setting->getSettingValue();
        }
    }

    public function renderRecentPosts(\Twig_Environment $twig, $tagSlug, $limit = 4)
    {
        $tag = $this->doctrine->getRepository('DataBundle:Tag')->findOneBySlug($tagSlug);

        if (!$tag) {
            return '';
        }

        $posts = $this->doctrine->getRepository('DataBundle:BlogPost')
            ->findByTagOrdered($tag, 'desc', $limit)
        ;

        return $twig->render('_recent_posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function renderTwig(\Twig_Environment $twig, $tmpl)
    {
        $_twig = clone $twig;
        $_twig->setLoader(new \Twig_Loader_String());

        return $_twig->render($twig->createTemplate($tmpl));
    }

    public function renderPartial(\Twig_Environment $twig, $title, $parameters = [])
    {
        $partial = $this->doctrine->getRepository('DataBundle:Partial')->findOneByTitle($title);

        if (!$partial || !$partial->getActive()) {
            return;
        }

        $tmpl = $twig->createTemplate($partial->getContent());

        return $tmpl->render($parameters);
    }

    public function getName()
    {
        return 'app_extension';
    }
}
