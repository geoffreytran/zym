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
		$this->container = $container;
	}

	public function postGenerateSchema(GenerateSchemaEventArgs $args)
	{
		// Do nothing
	}
}
