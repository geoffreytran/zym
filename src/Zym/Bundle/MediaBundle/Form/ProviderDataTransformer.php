<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zym\Bundle\MediaBundle\Form;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Zym\Bundle\MediaBundle\MediaPool;
use Symfony\Component\Form\DataTransformerInterface;

class ProviderDataTransformer implements DataTransformerInterface
{
    /**
     * Media Pool
     *
     * @var MediaPool
     */
    protected $mediaPool;

    /**
     * Options
     *
     * @var array
     */
    protected $options;

    /**
     * @param MediaPool $mediaPool
     * @param array $options
     */
    public function __construct(MediaPool $mediaPool, array $options = array())
    {
        $this->mediaPool    = $mediaPool;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }

        if (!$media->getProviderName() && isset($this->options['provider'])) {
            $media->setProviderName($this->options['provider']);
        }

        if (!$media->getContext() && isset($this->options['context'])) {
            $media->setContext($this->options['context']);
        }

        $provider = $this->mediaPool->getProvider($media->getProviderName());
        $provider->transform($media);

        return $media;
    }
}