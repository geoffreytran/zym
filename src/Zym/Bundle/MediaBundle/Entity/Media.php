<?php

namespace Zym\Bundle\MediaBundle\Entity;

use Zym\Bundle\MediaBundle\Model\AbstractMedia;
use Zym\Bundle\MediaBundle\Model\MediaInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Media
 *
 * @ORM\Entity(repositoryClass="MediaRepository")
 * @ORM\Table(name="media")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 * @ORM\HasLifecycleCallbacks()
 */
class Media extends AbstractMedia
            implements MediaInterface {
    /**
     * ID
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="text", length=1024, nullable=true)
     */
    protected $description;

    /**
     * Provider Name
     *
     * @var string
     *
     * @ORM\Column(name="provider_name", type="string")
     */
    protected $providerName;

    /**
     * Provider Status
     *
     * @var integer
     *
     * @ORM\Column(name="provider_status", type="integer")
     */
    protected $providerStatus;

    /**
     * Provider Reference
     *
     * @var string
     *
     * @ORM\Column(name="provider_reference", type="string")
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
     *
     * @ORM\Column(name="provider_metadata", type="json_array", nullable=true)
     */
    protected $providerMetadata = array();

    /**
     * Width
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $width;

    /**
     * Height
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $height;

    /**
     * Length
     *
     * @var decimal
     *
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $length;

    /**
     * Content Type
     *
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", nullable=true, length=64)
     */
    protected $contentType;

    /**
     * Size
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $size;

    /**
     * Copyright
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $copyright;

    /**
     * Author Name
     *
     * @var string
     *
     * @ORM\Column(name="author_name", type="string", nullable=true)
     */
    protected $authorName;

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
     *
     * @ORM\Column(type="string", nullable=true, length=16)
     */
    protected $context;

    /**
     * Whether the cdn is flushable
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="cdn_flushable", nullable=true)
     */
    protected $cdnFlushable = false;

    /**
     * CDN Status
     *
     * @var integer
     *
     * @ORM\Column(name="cdn_status",type="integer", nullable=true)
     */
    protected $cdnStatus;

    /**
     * CDN Flushed at
     *
     * @var \DateTime
     *
     * @ORM\Column(name="cdn_flushed_at", type="datetime", nullable=true)
     */
    protected $cdnFlushedAt;

    /**
     * CreatedAt
     *
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * Updated At
     *
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * Active
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    protected $galleryHasMedias;

    /**
     * Get the id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}