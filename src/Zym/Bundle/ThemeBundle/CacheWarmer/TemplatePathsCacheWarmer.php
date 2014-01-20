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

namespace Zym\Bundle\ThemeBundle\CacheWarmer;

use Zym\Bundle\ThemeBundle\ThemeManager;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplatePathsCacheWarmer as BaseTemplatePathsCacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;

/**
 * Class TemplatePathsCacheWarmer
 *
 * @package Zym\Bundle\ThemeBundle\CacheWarmer
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class TemplatePathsCacheWarmer extends BaseTemplatePathsCacheWarmer
{
    /**
     * Theme Manager
     *
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * Constructor.
     *
     * @param TemplateFinderInterface   $finder  A template finder
     * @param TemplateLocator           $locator The template locator
     */
    public function __construct(TemplateFinderInterface $finder, TemplateLocator $locator, ThemeManager $themeManager = null)
    {
        $this->themeManager = $themeManager;

        parent::__construct($finder, $locator);
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        if (empty($this->themeManager)) {
            return;
        }

        $locator = $this->locator->getLocator();

        $allTemplates = $this->finder->findAllTemplates();

        $curTheme  = $this->themeManager->getActiveTheme();
        $templates = array();

        foreach ($this->themeManager->getThemes() as $theme) {
            $this->themeManager->setActiveTheme($theme);

            foreach ($allTemplates as $template) {
                $templates[$template->getLogicalName() . '|' . $theme] = $locator->locate($template->getPath());
            }
        }

        $this->themeManager->setActiveTheme($curTheme);

        $this->writeCacheFile($cacheDir . '/templates.php', sprintf('<?php return %s;', var_export($templates, true)));
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * @return Boolean always true
     */
    public function isOptional()
    {
        return true;
    }
}
