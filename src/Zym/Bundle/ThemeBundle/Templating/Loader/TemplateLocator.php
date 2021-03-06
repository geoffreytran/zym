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

namespace Zym\Bundle\ThemeBundle\Templating\Loader;

use Zym\Bundle\ThemeBundle\ThemeManager;
use Zym\Bundle\ThemeBundle\Templating\TemplateReference as ThemeTemplateReference;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator as BaseTemplateLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TemplateLocator extends BaseTemplateLocator
{
    /**
     * Theme Manager
     *
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * Constructor.
     *
     * @param FileLocatorInterface $locator  A FileLocatorInterface instance
     * @param string               $cacheDir The cache path
     * @param ActiveTheme          $theme    The theme instance
     */
    public function __construct(FileLocatorInterface $locator, $cacheDir = null, ThemeManager $themeManager = null)
    {
        $this->themeManager = $themeManager;

        parent::__construct($locator, $cacheDir);
    }

    public function getLocator()
    {
       return $this->locator;
    }

    /**
     * Returns a full path for a given file.
     *
     * @param TemplateReferenceInterface $template     A template
     *
     * @return string The full path for the file
     */
    protected function getCacheKey($template)
    {
        $name = $template->getLogicalName();

        if ($this->themeManager->getActiveTheme()) {
            $name .= '|' . $this->themeManager->getActiveTheme();
        }

        return $name;
    }

    /**
     * Returns a full path for a given file.
     *
     * @param TemplateReferenceInterface $template     A template
     * @param string                     $currentPath  Unused
     * @param Boolean                    $first        Unused
     *
     * @return string The full path for the file
     *
     * @throws \InvalidArgumentException When the template is not an instance of TemplateReferenceInterface
     * @throws \InvalidArgumentException When the template file can not be found
     */
    public function locate($template, $currentPath = null, $first = true)
    {
        if (!$template instanceof TemplateReferenceInterface) {
            throw new \InvalidArgumentException("The template must be an instance of TemplateReferenceInterface.");
        }

        $curTheme = $this->themeManager->getActiveTheme();
        if ($template instanceof ThemeTemplateReference && $template->get('theme')) {
            $this->themeManager->setActiveTheme($template->get('theme'));
        }

        $key = $this->getCacheKey($template);

        if (!isset($this->cache[$key])) {
            try {
                $this->cache[$key] = $this->locator->locate($template->getPath(), $currentPath);
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException(sprintf('Unable to find template "%s" in "%s".', $template, $e->getMessage()), 0, $e);
            }
        }

        $this->themeManager->setActiveTheme($curTheme);

        return $this->cache[$key];
    }
}