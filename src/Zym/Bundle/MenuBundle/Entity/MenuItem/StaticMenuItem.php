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

namespace Zym\Bundle\MenuBundle\Entity\MenuItem;

use Zym\Bundle\MenuBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Static Menu Item
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Entity(repositoryClass="Zym\Bundle\MenuBundle\Entity\MenuItemRepository")
 */
class StaticMenuItem extends Entity\MenuItem
{
    /**
     * URI
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $uri;
}