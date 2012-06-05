<?php

namespace Zym\Bundle\ThemeBundle\CacheWarmer;

use Zym\Bundle\ThemeBundle\ThemeManager;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplatePathsCacheWarmer as BaseTemplatePathsCacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;

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

        $templates = array();
        foreach ($this->themeManager->getThemes() as $theme) {
            $this->themeManager>setActiveTheme($theme);
            
            foreach ($allTemplates as $template) {
                $templates[$template->getLogicalName() . '|' . $theme] = $locator->locate($template->getPath());
            }
        }

        $this->writeCacheFile($cacheDir.'/templates.php', sprintf('<?php return %s;', var_export($templates, true)));
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
