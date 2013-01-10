<?php

namespace Zym\Bundle\MediaBundle\CDN;

class Server implements CDNInterface
{
    protected $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($relativePath, $isFlushable)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }

    /**
     * {@inheritDoc}
     */
    public function flushPath($path)
    {
        // nothing to do
    }

    /**
     * {@inheritDoc}
     */
    public function flushPaths(array $paths)
    {
        // nothing to do
    }
}