<?php

/**
 * Zym Framework
 *
 * This file is part of the Zym package.
 *
 * @link      https://github.com/geoffreytran/zym for the canonical source repository
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3 License
 */

namespace Zym\Bundle\ContentBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

/**
 * Class LoadPageData
 *
 * @package Zym\Bundle\ContentBundle\DataFixtures\PHPCR
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class LoadPageData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basePath = $this->container->getParameter('cmf_simple_cms.persistence.phpcr.basepath');
        NodeHelper::createPath($session, preg_replace('#/[^/]*$#', '', $basePath));

        $root     = $manager->find(null, $basePath);

        if (!$root) {
            $root = new Page();
            $root->setId($basePath);
            $root->setTitle('Home Page');
        }

        $manager->persist($root);


        $manager->flush();
    }

    protected function createPage(ObjectManager $manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page);

        return $page;
    }

    public function getOrder()
    {
        return 10;
    }
}