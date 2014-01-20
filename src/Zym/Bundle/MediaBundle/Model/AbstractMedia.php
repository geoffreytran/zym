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

namespace Zym\Bundle\MediaBundle\Model;

use Imagine\Image\Box;

abstract class AbstractMedia implements MediaInterface
{
    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * Description
     *
     * @var string
     */
    protected $description;

    /**
     * Active
     *
     * @var boolean
     */
    protected $active = false;

    /**
     * Provider Name
     *
     * @var string
     */
    protected $providerName;

    /**
     * Provider Status
     *
     * @var integer
     */
    protected $providerStatus;

    /**
     * Provider Reference
     *
     * @var string
     */
    protected $providerReference;

    /**
     * Previous Provider Reference
     *
     * @var string
     */
    protected $previousProviderReference;

    /**
     * Provider Metadata
     *
     * @var array
     */
    protected $providerMetadata = array();

    /**
     * Width
     *
     * @var integer
     */
    protected $width;

    /**
     * Height
     *
     * @var integer
     */
    protected $height;

    /**
     * Length
     *
     * @var decimal
     */
    protected $length;

    /**
     * Size
     *
     * @var integer
     */
    protected $size;

    /**
     * Copyright
     *
     * @var string
     */
    protected $copyright;

    /**
     * Author Name
     *
     * @var string
     */
    protected $authorName;

    /**
     * Content Type
     *
     * @var string
     */
    protected $contentType;

    /**
     * Binary Content
     *
     * @var mixed
     */
    protected $binaryContent;

    /**
     * Context
     *
     * @var string
     */
    protected $context;

    /**
     * Whether the cdn is flushable
     *
     * @var boolean
     */
    protected $cdnFlushable = false;

    /**
     * CDN Status
     *
     * @var integer
     */
    protected $cdnStatus;

    /**
     * CDN Flushed at
     *
     * @var \DateTime
     */
    protected $cdnFlushedAt;

    /**
     * CreatedAt
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Updated At
     *
     * @var \DateTime
     */
    protected $updatedAt;

    protected $galleryHasMedias;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @static
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_OK          => 'ok',
            self::STATUS_SENDING     => 'sending',
            self::STATUS_PENDING     => 'pending',
            self::STATUS_ERROR       => 'error',
            self::STATUS_ENCODING    => 'encoding',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderStatus($providerStatus)
    {
        $this->providerStatus = $providerStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderStatus()
    {
        return $this->providerStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderReference($providerReference)
    {
        $this->providerReference = $providerReference;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderReference()
    {
        return $this->providerReference;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousProviderReference()
    {
        return $this->previousProviderReference;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderMetadata(array $providerMetadata = array())
    {
        $this->providerMetadata = $providerMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return $this->providerMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataValue($name, $default = null)
    {
        $metadata = $this->getProviderMetadata();

        return isset($metadata[$name]) ? $metadata[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataValue($name, $value)
    {
        $metadata = $this->getProviderMetadata();
        $metadata[$name] = $value;
        $this->setProviderMetadata($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function unsetMetadataValue($name)
    {
        $metadata = $this->getProviderMetadata();
        unset($metadata[$name]);
        $this->setProviderMetadata($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function getBox()
    {
        return new Box($this->width, $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }

    public function getHumanSize()
    {
        if ($this->size >= 1073741824) {
            return round($this->size / 1073741824, 2) . ' GB';
        }

        if ($this->size >= 1048576) {
            return round($this->size / 1048576, 2) . ' MB';
        }

        if ($this->size >= 1024) {
            return round($this->size / 1024, 0) . ' KB';
        }

        return $this->size . ' B';
    }

    /**
     * {@inheritdoc}
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     * {@inheritdoc}
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return pathinfo($this->getProviderReference(), PATHINFO_EXTENSION);
    }

    /**
     * {@inheritdoc}
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    /**
     * {@inheritdoc}
     */
    public function setBinaryContent($binaryContent)
    {
        $this->previousProviderReference = $this->providerReference;
        $this->providerReference = null;
        $this->binaryContent = $binaryContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinaryContent()
    {
        return $this->binaryContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function setCdnFlushable($cdnFlushable)
    {
        $this->cdnFlushable = (bool)$cdnFlushable;
    }

    /**
     * {@inheritdoc}
     */
    public function IsCdnFlushable()
    {
        return (bool) $this->cdnFlushable;
    }

    /**
     * {@inheritdoc}
     */
    public function setCdnStatus($cdnStatus)
    {
        $this->cdnStatus = $cdnStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnStatus()
    {
        return $this->cdnStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function setCdnFlushedAt(\DateTime $cdnFlushedAt = null)
    {
        $this->cdnFlushedAt = $cdnFlushedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnFlushedAt()
    {
        return $this->cdnFlushedAt;
    }

     /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

     /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }

    /**
     * {@inheritdoc}
     */
    public function setGalleryHasMedias($galleryHasMedias)
    {
        $this->galleryHasMedias = $galleryHasMedias;
    }

    /**
     * {@inheritdoc}
     */
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }
}