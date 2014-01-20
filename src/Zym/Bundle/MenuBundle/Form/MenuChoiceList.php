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

namespace Zym\Bundle\MenuBundle\Form;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpKernel\Kernel;

use Symfony\Component\Form\Util\PropertyPath as DepPropertyPath;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyAccess;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\Form\Exception\StringCastException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\InvalidPropertyException;
use Doctrine\Common\Persistence\ObjectManager;

class MenuChoiceList extends ChoiceList
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    private $classMetadata;

    /**
     * Contains the query builder that builds the query for fetching the
     * entities
     *
     * This property should only be accessed through queryBuilder.
     *
     * @var EntityLoaderInterface
     */
    private $entityLoader;

    /**
     * The identifier field, if the identifier is not composite
     *
     * @var array
     */
    private $idField = null;

    /**
     * Whether to use the identifier for index generation
     *
     * @var Boolean
     */
    private $idAsIndex = false;

    /**
     * Whether to use the identifier for value generation
     *
     * @var Boolean
     */
    private $idAsValue = false;

    /**
     * Whether the entities have already been loaded.
     *
     * @var Boolean
     */
    private $loaded = false;

    /**
     * The property path used to obtain the choice label.
     *
     * @var PropertyPath
     */
    private $labelPath;

    /**
     * The property path used for object grouping.
     *
     * @var PropertyPath
     */
    private $groupPath;

    /**
     * Creates a new entity choice list.
     *
     * @param ObjectManager         $manager      An EntityManager instance
     * @param string                $class        The class name
     * @param string                $labelPath    The property path used for the label
     * @param EntityLoaderInterface $entityLoader An optional query builder
     * @param array                 $entities     An array of choices
     * @param string                $groupPath    A property path pointing to the property used
     *                                            to group the choices. Only allowed if
     *                                            the choices are given as flat array.
     */
    public function __construct(ObjectManager $manager, $class, $labelPath = null, EntityLoaderInterface $entityLoader = null, $entities = null, $groupPath = null)
    {
        $this->em = $manager;
        $this->entityLoader = $entityLoader;
        $this->classMetadata = $manager->getClassMetadata($class);
        $this->class = $this->classMetadata->getName();
        $this->loaded = is_array($entities) || $entities instanceof \Traversable;

        $identifier = $this->classMetadata->getIdentifierFieldNames();

        if (1 === count($identifier)) {
            $this->idField = $identifier[0];
            $this->idAsValue = true;

            if ('integer' === $this->classMetadata->getTypeOfField($this->idField)) {
                $this->idAsIndex = true;
            }
        }

        if (!$this->loaded) {
            // Make sure the constraints of the parent constructor are
            // fulfilled
            $entities = array();
        }

        if (version_compare(Kernel::VERSION, '2.1') <= 0) {
            $this->labelPath = $labelPath ? new DepPropertyPath($labelPath) : null;
            $this->groupPath = $groupPath ? new DepPropertyPath($groupPath) : null;
        } else {
            $this->labelPath = $labelPath ? new PropertyPath($labelPath) : null;
            $this->groupPath = $groupPath ? new PropertyPath($groupPath) : null;
        }


        parent::__construct($entities, array(), array());
    }

    /**
     * Returns the list of entities
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getChoices()
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::getChoices();
    }

    /**
     * Returns the values for the entities
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getValues()
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::getValues();
    }

    /**
     * Returns the choice views of the preferred choices as nested array with
     * the choice groups as top-level keys.
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getPreferredViews()
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::getPreferredViews();
    }

    /**
     * Returns the choice views of the choices that are not preferred as nested
     * array with the choice groups as top-level keys.
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getRemainingViews()
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::getRemainingViews();
    }

    /**
     * Returns the entities corresponding to the given values.
     *
     * @param array $values
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getChoicesForValues(array $values)
    {
        if (!$this->loaded) {
            // Optimize performance in case we have an entity loader and
            // a single-field identifier
            if ($this->idAsValue && $this->entityLoader) {
                if (empty($values)) {
                    return array();
                }

                return $this->entityLoader->getEntitiesByIds($this->idField, $values);
            }

            $this->load();
        }

        return parent::getChoicesForValues($values);
    }

    /**
     * Returns the values corresponding to the given entities.
     *
     * @param array $entities
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getValuesForChoices(array $entities)
    {
        if (!$this->loaded) {
            // Optimize performance for single-field identifiers. We already
            // know that the IDs are used as values

            // Attention: This optimization does not check choices for existence
            if ($this->idAsValue) {
                $values = array();

                foreach ($entities as $entity) {
                    if ($entity instanceof $this->class) {
                        // Make sure to convert to the right format
                        $values[] = $this->fixValue(current($this->getIdentifierValues($entity)));
                    }
                }

                return $values;
            }

            $this->load();
        }

        return parent::getValuesForChoices($entities);
    }

    /**
     * Returns the indices corresponding to the given entities.
     *
     * @param array $entities
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getIndicesForChoices(array $entities)
    {
        if (!$this->loaded) {
            // Optimize performance for single-field identifiers. We already
            // know that the IDs are used as indices

            // Attention: This optimization does not check choices for existence
            if ($this->idAsIndex) {
                $indices = array();

                foreach ($entities as $entity) {
                    if ($entity instanceof $this->class) {
                        // Make sure to convert to the right format
                        $indices[] = $this->fixIndex(current($this->getIdentifierValues($entity)));
                    }
                }

                return $indices;
            }

            $this->load();
        }

        return parent::getIndicesForChoices($entities);
    }

    /**
     * Returns the entities corresponding to the given values.
     *
     * @param array $values
     *
     * @return array
     *
     * @see Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    public function getIndicesForValues(array $values)
    {
        if (!$this->loaded) {
            // Optimize performance for single-field identifiers.

            // Attention: This optimization does not check values for existence
            if ($this->idAsIndex && $this->idAsValue) {
                return $this->fixIndices($values);
            }

            $this->load();
        }

        return parent::getIndicesForValues($values);
    }

    /**
     * Creates a new unique index for this entity.
     *
     * If the entity has a single-field identifier, this identifier is used.
     *
     * Otherwise a new integer is generated.
     *
     * @param mixed $choice The choice to create an index for
     *
     * @return integer|string A unique index containing only ASCII letters,
     *                        digits and underscores.
     */
    protected function createIndex($entity)
    {
        if ($this->idAsIndex) {
            return current($this->getIdentifierValues($entity));
        }

        return parent::createIndex($entity);
    }

    /**
     * Creates a new unique value for this entity.
     *
     * If the entity has a single-field identifier, this identifier is used.
     *
     * Otherwise a new integer is generated.
     *
     * @param mixed $choice The choice to create a value for
     *
     * @return integer|string A unique value without character limitations.
     */
    protected function createValue($entity)
    {
        if ($this->idAsValue) {
            return (string) current($this->getIdentifierValues($entity));
        }

        return parent::createValue($entity);
    }

    /**
     * Loads the list with entities.
     */
    private function load()
    {
        if ($this->entityLoader) {
            $entities = $this->entityLoader->getEntities();
        } else {
            $entities = $this->em->getRepository($this->class)->findAll();
        }

        try {
            // The second parameter $labels is ignored by ObjectChoiceList
            // The third parameter $preferredChoices is currently not supported
            $this->initialize($entities, array(), array());
        } catch (StringCastException $e) {
            throw new StringCastException(str_replace('argument $labelPath', 'option "property"', $e->getMessage()), null, $e);
        }

        $this->loaded = true;
    }

    /**
     * Returns the values of the identifier fields of an entity.
     *
     * Doctrine must know about this entity, that is, the entity must already
     * be persisted or added to the identity map before. Otherwise an
     * exception is thrown.
     *
     * @param object $entity The entity for which to get the identifier
     *
     * @return array          The identifier values
     *
     * @throws FormException  If the entity does not exist in Doctrine's identity map
     */
    private function getIdentifierValues($entity)
    {
        if (!$this->em->contains($entity)) {
            throw new FormException('Entities passed to the choice field must be managed');
        }

        $this->em->initializeObject($entity);

        return $this->classMetadata->getIdentifierValues($entity);
    }


    /**
     * Initializes the list with choices.
     *
     * Safe to be called multiple times. The list is cleared on every call.
     *
     * @param array|\Traversable $choices          The choices to write into the list.
     * @param array              $labels           Ignored.
     * @param array              $preferredChoices The choices to display with priority.
     */
    protected function initialize($choices, array $labels, array $preferredChoices)
    {
        if (!is_array($choices) && !$choices instanceof \Traversable) {
            throw new UnexpectedTypeException($choices, 'array or \Traversable');
        }

        if (null !== $this->groupPath) {
            $groupedChoices = array();

            foreach ($choices as $i => $choice) {
                if (is_array($choice)) {
                    throw new \InvalidArgumentException('You should pass a plain object array (without groups, $code, $previous) when using the "groupPath" option');
                }

                try {
                    $group = $this->groupPath->getValue($choice);
                } catch (InvalidPropertyException $e) {
                    // Don't group items whose group property does not exist
                    // see https://github.com/symfony/symfony/commit/d9b7abb7c7a0f28e0ce970afc5e305dce5dccddf
                    $group = null;
                }

                if (null === $group) {
                    $groupedChoices[$i] = $choice;
                } else {
                    if (!isset($groupedChoices[$group])) {
                        $groupedChoices[$group] = array();
                    }

                    $groupedChoices[$group][$i] = $choice;
                }
            }

            $choices = $groupedChoices;
        }


        $labels = array();

        $this->extractLabels($choices, $labels);

        parent::initialize($choices, $labels, $preferredChoices);
    }

    private function extractLabels($choices, array &$labels)
    {
        foreach ($choices as $i => $choice) {
            if ((is_array($choice) || $choice instanceof \Traversable) && !$choice instanceof ItemInterface) {
                $labels[$i] = array();
                $this->extractLabels($choice, $labels[$i]);
            } elseif ($this->labelPath) {
                //$labels[$i] = $this->labelPath->getValue($choice);

                if (version_compare(Kernel::VERSION, '2.1') <= 0) {
                    $labelValue = $this->labelPath->getValue($choice);
                } else {
                    $propertyAccessor = PropertyAccess::getPropertyAccessor();
                    $labelValue = $propertyAccessor->getValue($choice, $this->labelPath);
                }
                
                $labels[$i] = str_repeat('-', $choice->getLevel()) . ' ' . $labelValue;
            } elseif (method_exists($choice, '__toString')) {
                $labels[$i] = (string) $choice;
            } else {
                throw new StringCastException('A "__toString()" method was not found on the objects of type "' . get_class($choice) . '" passed to the choice field. To read a custom getter instead, set the argument $labelPath to the desired property path.');
            }
        }
    }
}