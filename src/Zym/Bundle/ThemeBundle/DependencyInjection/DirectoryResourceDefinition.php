<?php

namespace Zym\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Bundle\AsseticBundle\DependencyInjection\DirectoryResourceDefinition as BaseDirectoryResourceDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Encapsulates logic for creating a directory resource.
 *
 */
class DirectoryResourceDefinition extends Definition
{
    /**
     * Constructor.
     *
     * @param string $bundle A bundle name or empty string
     * @param string $engine The templating engine
     * @param array  $dirs   An array of directories to merge
     */
    public function __construct($bundle, $engine, array $dirs)
    {
        if (!count($dirs)) {
            throw new \InvalidArgumentException('You must provide at least one directory.');
        }

        parent::__construct();
        $this
            ->addTag('assetic.templating.'.$engine)
            ->addTag('assetic.formula_resource', array('loader' => $engine));
        ;

        if (1 == count($dirs)) {
            // no need to coalesce
            self::configureDefinition($this, $bundle, $engine, reset($dirs), key($dirs));
            return;
        }

        // gather the wrapped resource definitions
        $resources = array();
        foreach ($dirs as $theme => $dir) {
            $resources[] = $resource = new Definition();
            self::configureDefinition($resource, $bundle, $engine, $dir, $theme);
        }

        $this
            ->setClass('%assetic.coalescing_directory_resource.class%')
            ->addArgument($resources)
            ->setPublic(false)
        ;
    }

    static private function configureDefinition(Definition $definition, $bundle, $engine, $dir, $theme = null)
    {
        $definition
            ->setClass('%assetic.directory_resource.class%')
            ->addArgument(new Reference('templating.loader'))
            ->addArgument($bundle)
            ->addArgument($dir)
            ->addArgument('/\.[^.]+\.'.$engine.'$/')
            ->addArgument($theme)
            ->setPublic(false)
        ;
    }
}
