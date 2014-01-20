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

namespace Zym\Security\Acl\Domain;

use Symfony\Component\Security\Acl\Domain\ObjectIdentityRetrievalStrategy as BaseObjectIdentityRetrievalStrategy;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;

/**
 * Class ObjectIdentityRetrievalStrategy
 *
 * Strategy to be used for retrieving object identities from domain objects
 *
 * @package Zym\Security\Acl\Domain
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ObjectIdentityRetrievalStrategy extends BaseObjectIdentityRetrievalStrategy
{
    /**
     * {@inheritDoc}
     */
    public function getObjectIdentity($domainObject)
    {
        try {
            if (is_array($domainObject) && count($domainObject) == 2) {
                return new ObjectIdentity($domainObject[0], $domainObject[1]);
            }

            return parent::getObjectIdentity($domainObject);
        } catch (InvalidDomainObjectException $failed) {
            return null;
        }
    }
}
