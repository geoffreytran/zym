<?php

namespace Zym\Bundle\MediaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class File extends Media
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->setProviderName('zym_media.provider.file');
    }

}