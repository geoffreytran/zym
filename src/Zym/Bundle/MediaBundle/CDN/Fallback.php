<?php

namespace Zym\Bundle\MediaBundle\CDN;

class Fallback implements CDNInterface
{
    protected $cdn;

    protected $fallback;

    /**
     * @param CDNInterface $cdn
     * @param CDNInterface $fallback
     */
    public function __construct(CDNInterface $cdn, CDNInterface $fallback)
    {
        $this->cdn      = $cdn;
        $this->fallback = $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($relativePath, $isFlushable)
    {
        if ($isFlushable) {
            return $this->fallback->getPath($relativePath, $isFlushable);
        }

        return $this->cdn->getPath($relativePath, $isFlushable);
    }

    /**
     * {@inheritdoc}
     */
    public function flushPath($path)
    {
        $this->cdn->flushPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function flushPaths(array $paths)
    {
        $this->cdn->flushPaths($paths);
    }
}