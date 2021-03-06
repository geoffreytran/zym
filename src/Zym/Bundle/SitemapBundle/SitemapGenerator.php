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

namespace Zym\Bundle\SitemapBundle;

use Presta\SitemapBundle\Service\Generator as BaseGenerator;
use Doctrine\Common\Cache\Cache;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Presta\SitemapBundle\Sitemap\Sitemapindex;
use Presta\SitemapBundle\Sitemap\Url\Url;

class SitemapGenerator extends BaseGenerator
{
    /**
     * Generate all datas and store in cache if it is possible
     *
     * @return void
     */
    public function generate($section = null)
    {
        $this->populate($section == 'root' ? null : $section);

        //---------------------
        //---------------------
        // cache management
        if ($this->cache) {
            $ttl = $this->dispatcher->getContainer()->getParameter('presta_sitemap.timetolive');

            if ($section == null || $section == 'root') {
                $this->cache->save('root', serialize($this->root), $ttl);
            } else {
                $urlset = $this->getUrlset($section);
                $this->cache->save($section, serialize($urlset), $ttl);
            }
        }
        //---------------------
    }

    /**
     * Get eventual cached data or generate whole sitemap
     *
     * @param string $name
     * @return Sitemapindex or Urlset - can be <null>
     */
    public function fetch($name)
    {
        if ($this->cache && $this->cache->contains($name)) {
            return unserialize($this->cache->fetch($name));
        }

        $this->generate($name);

        if ('root' == $name) {
            return $this->root;
        }

        if (array_key_exists($name, $this->urlsets)) {
            return $this->urlsets[$name];
        }

        return null;
    }
}