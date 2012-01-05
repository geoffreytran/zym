<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Zym\Bundle\SecurityBundle\Entity\AclSecurityIdentity;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

/**
 * Transforms between a given value and a string.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class AclSecurityIdentityToArrayTransformer implements DataTransformerInterface
{
    private $choiceList;

    public function __construct(EntityChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
    }
    
    /**
     * Transforms a value into a string.
     *
     * @param  mixed  $value   Mixed value.
     *
     * @return string          String value.
     *
     * @throws UnexpectedTypeException if the given value is not a string or number
     */
    public function transform($value)
    {   
        $roles = array();

        $entities = $this->choiceList->getEntities();
        foreach ($entities as $entity) {
            if (in_array($entity->getIdentifier(), (array)$value)) {
                $roles[] = $entity->getId();
            }
        }
        
        return $roles;
    }

    /**
     * Transforms a value into a string.
     *
     * @param  string $value  String value.
     *
     * @return string         String value.
     *
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value)
    {
        $roles = array();

        if (!is_array($value)) {
            throw new UnexpectedTypeException($keys, 'array');
        }

        $entities = $this->choiceList->getEntitiesByKeys($value);
        if (count($value) !== count($entities)) {
            throw new TransformationFailedException('Not all entities matching the keys were found.');
        }

        foreach ($entities as $entity) {
            $roles[] = $entity->getIdentifier();
        }

        return $roles;
    }
}