<?php

namespace BackendBundle\Service;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
class PageService
{
    private $pageRepository;
    private $em;

    public function __construct($pageRepository, $em)
    {
        $this->pageRepository = $pageRepository;
        $this->em = $em;
    }

    public function restoreOrder()
    {
        $pages = $this->pageRepository->findBy(
            [],
            ['ordering' => 'asc']
        );

        $i = 0;

        foreach ($pages as $page) {
            $page->setOrdering($i);

            ++$i;
        }

        $this->em->flush();
    }
}
