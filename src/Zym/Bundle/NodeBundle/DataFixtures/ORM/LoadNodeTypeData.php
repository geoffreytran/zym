<?php
namespace Zym\Bundle\NodeBundle\DataFixtures\ORM;

use Zym\Bundle\FieldBundle\Entity as FieldEntity;
use Zym\Bundle\NodeBundle\Entity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class LoadNodeTypeData extends AbstractFixture
                  implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load
     *
     * @param object $manager
     */
    public function load($manager)
    {
        $nodeType = new Entity\NodeType();
        $nodeType->setType('basic_page');
        $nodeType->setName('Basic Page');
        $nodeType->setDescription('<p>Use basic pages for your static content, such as an &quot;About Us&quot; page.</p>');
        
        $nodeTypeManager = $this->container->get('zym_node.node_type_manager');
        $nodeTypeManager->createNodeType($nodeType);
        
        $bodyFieldType = new FieldEntity\FieldType();
        $bodyFieldType->setMachineName('body');
        $bodyFieldType->setType('Zym\Bundle\FieldBundle\Entity\LongTextField');
        $bodyFieldType->setValueCount(1);
        $manager->persist($bodyFieldType);
        
        $bodyFieldConfig = new Entity\NodeFieldConfig();
        $bodyFieldConfig->setNodeType($nodeType);
        $bodyFieldConfig->setFieldType($bodyFieldType);
        $bodyFieldConfig->setLabel('Body');
        $bodyFieldConfig->setWidget('textarea');
        $manager->persist($bodyFieldConfig);
        
        $manager->flush();        
    }
    
    /**
     * Get the order in which fixtures will be loaded
     * 
     * @return integer
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
    
    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}