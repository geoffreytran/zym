<?php

namespace Zym\Bundle\SecurityBundle\Twig\Extension;

class SecurityExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'acl_mask_to_array' => new \Twig_Function_Method($this, 'aclMaskToArray', array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns an array of mask strings from the single mask value
     *
     * @param integer $value
     * @return boolean
     */
    public function aclMaskToArray($value)
    {
        $values = array();

        $reflection = new \ReflectionClass('Symfony\Component\Security\Acl\Permission\MaskBuilder');
        foreach ($reflection->getConstants() as $name => $cMask) {
            $cName = substr($name, 5);

            if (0 === strpos($name, 'MASK_')) {
                $maskValue = constant('Symfony\Component\Security\Acl\Permission\MaskBuilder::' . $name);

                if (($value & $maskValue) == $maskValue) {
                    $values[strtolower($cName)] = ucwords(strtolower($cName));
                }
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zym_security';
    }
}