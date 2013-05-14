<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zym\Bundle\SecurityBundle\EventListener;

use Symfony\Bundle\SecurityBundle\EventListener\AclSchemaListener as BaseAclSchemaListener;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;

/**
 * Merges ACL schema into the given schema.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AclSchemaListener extends BaseAclSchemaListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->container = $container;
    }

    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args)
    {
        switch ($args->getClassMetadata()->getName()) {
            case 'Zym\Bundle\SecurityBundle\Entity\AclClass':                
            case 'Zym\Bundle\SecurityBundle\Entity\AclEntry':                
            case 'Zym\Bundle\SecurityBundle\Entity\AclObjectIdentity':                
            case 'Zym\Bundle\SecurityBundle\Entity\AclSecurityIdentity':
                $tableName = $args->getClassTable()->getName();
                
                $schema = $args->getSchema();
                $schema->dropTable($tableName);
                break;
            
            default:
        }
    }
}
