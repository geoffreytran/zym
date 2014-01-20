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

namespace Zym\Bundle\ThemeBundle\Templating\Resource;

use Assetic\Factory\Resource\IteratorResourceInterface;

/**
 * Class CoalescingDirectoryResource
 *
 * @package Zym\Bundle\ThemeBundle\Templating\Resource
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class CoalescingDirectoryResource implements IteratorResourceInterface
{
    private $directories;

    public function __construct($directories)
    {
        $this->directories = array();

        foreach ($directories as $directory) {
            $this->addDirectory($directory);
        }
    }

    public function addDirectory(IteratorResourceInterface $directory)
    {
        $this->directories[] = $directory;
    }

    public function isFresh($timestamp)
    {
        foreach ($this->getFileResources() as $file) {
            if (!$file->isFresh($timestamp)) {
                return false;
            }
        }

        return true;
    }

    public function getContent()
    {
        $parts = array();
        foreach ($this->getFileResources() as $file) {
            $parts[] = $file->getContent();
        }

        return implode("\n", $parts);
    }

    /**
     * Returns a string to uniquely identify the current resource.
     *
     * @return string An identifying string
     */
    public function __toString()
    {
        $parts = array();
        foreach ($this->directories as $directory) {
            $parts[] = (string) $directory;
        }

        return implode(',', $parts);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getFileResources());
    }

    /**
     * Returns the relative version of a filename.
     *
     * @param ResourceInterface $file      The file
     * @param ResourceInterface $directory The directory
     *
     * @return string The name to compare with files from other directories
     */
    protected function getRelativeName(ResourceInterface $file, ResourceInterface $directory)
    {
        $name = (string) $file;

        return substr($name, strpos($name, ':'));
    }

    /**
     * Performs the coalesce.
     *
     * @return array An array of file resources
     */
    private function getFileResources()
    {
        $paths = array();

        foreach ($this->directories as $directory) {
            foreach ($directory as $file) {
                $paths[] = $file;
            }
        }

        return array_values($paths);
    }
}
