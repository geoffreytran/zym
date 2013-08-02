<?php
namespace Zym\Bundle\RuntimeConfigBundle\Entity;

use OpenSky\Bundle\RuntimeConfigBundle\Model\Parameter as BaseParameter;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Yaml\Inline;
use Symfony\Component\Yaml\ParserException;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="ParameterRepository")
 * @ORM\Table(name="parameters")
 * 
 * @UniqueEntity(groups={"Entity"}, fields={"name"}, message="A parameter with the same name already exists; name must be unique")
 */
class Parameter extends BaseParameter
                implements DomainObjectInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Assert\Length(groups={"Entity"}, min="0", max="255")
     * @Assert\NotBlank(groups={"Entity"})
     */
    protected $name;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Assert\Length(groups={"Entity"}, min="0", max="255")
     */
    protected $value = array();

    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier()
    {
        return md5($this->getName());
    }

    public function validateValueAsYaml(ExecutionContext $context)
    {
        try {
            Inline::load($this->value);
        } catch (ParserException $e) {
            $context->setPropertyPath($context->getPropertyPath() . '.value');
            $context->addViolation('This value is not valid YAML syntax', array(), $this->value);
        }
    }
}