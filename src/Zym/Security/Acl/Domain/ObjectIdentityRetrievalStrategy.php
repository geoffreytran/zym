<?php
namespace Zym\Security\Acl\Domain;

use Symfony\Component\Security\Acl\Domain\ObjectIdentityRetrievalStrategy as BaseObjectIdentityRetrievalStrategy;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;

/**
 * Strategy to be used for retrieving object identities from domain objects
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
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
