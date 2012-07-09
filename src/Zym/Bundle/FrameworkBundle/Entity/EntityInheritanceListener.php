<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\FrameworkBundle\Entity;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Entity Inheritance Listener
 *
 * Automatically handle inheritance across all bundles.
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class EntityInheritanceListener implements EventSubscriber
{
    /**
     * AllClassNamesCache
     *
     * @var array
     */
    private $allClassNamesCache = array();

    /**
     * Get subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata
        );
    }

    /**
     * Load class metdata event
     *
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $configuration = $entityManager->getConfiguration();
        $driver        = $configuration->getMetadataDriverImpl();


        $classMetadata = $event->getClassMetadata();

        // Name of the current class
        $class = $classMetadata->name;

        if ($classMetadata->isMappedSuperclass) {
            return;
        }

        // Create an array that'll store our sub class names
        $values = array();

        // Build cache for classnames if not exists
        if (empty($this->allClassNamesCache)) {
            $this->allClassNamesCache = $driver->getAllClassNames();
        }

        // Loop through all the registered entities
        foreach($this->allClassNamesCache as $name) {
            // Reflect them
            try {
                $r = new \ReflectionClass($name);
    
                // and if it has a parent and the parent is our current entity
                if ($r->isSubclassOf($class)) {
                    // now store the name of the sub class in the array.
                    // we'll use the full class name as both the key and value for this.
                    $values[$name] = $name;
                }
            } catch (\ReflectionException $e) {
                // Most likely the class doesn't exist or could not be loaded
                // Assume Doctrine will handle this issue later...
                continue;
             }
        }

        if (!empty($values)) {
            $values[$class] = $class;

            if (!$classMetadata->discriminatorColumn) {
                $classMetadata->setDiscriminatorColumn(array('name' => 'object_type', 'type' => 'string', 'length' => 1024));
            }

            if ($classMetadata->inheritanceType == ClassMetadataInfo::INHERITANCE_TYPE_NONE) {
                $classMetadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_JOINED);
            }

            if (!empty($classMetadata->discriminatorMap)) {
                foreach ($classMetadata->discriminatorMap as $dName => $dClass) {
                    if (in_array($dClass, $values)) {
                        unset($values[$dClass]);
                    }
                }

                $values = array_merge($classMetadata->discriminatorMap, $values);
            }

            $classMetadata->setDiscriminatorMap($values);
        }
    }
}