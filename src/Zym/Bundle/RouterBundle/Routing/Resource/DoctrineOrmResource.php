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

namespace Zym\Bundle\RouterBundle\Routing\Resource;

use Doctrine\ORM;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Zym CMS Extension
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class DoctrineOrmResource implements ResourceInterface
{
    /**
     * EntityManager
     *
     * @var ORM\EntityManager
     */
    private $entityManager;

    /**
     * Resource
     *
     * @var string
     */
    private $resource;

    /**
     * Constructor.
     *
     * @param string $resource The file path to the resource
     */
    public function __construct($resource)
    {
        //$this->entityManager = $entityManager;
        $this->resource      = $resource;
    }

    /**
     * Returns a string representation of the Resource.
     *
     * @return string A string representation of the Resource
     */
    public function __toString()
    {
        return (string) $this->resource;
    }

    /**
     * Returns the resource tied to this Resource.
     *
     * @return mixed The resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns true if the resource has not been updated since the given timestamp.
     *
     * @param integer $timestamp The last time the resource was loaded
     *
     * @return Boolean true if the resource has not been updated, false otherwise
     */
    public function isFresh($timestamp)
    {
        return false;
    }
}
