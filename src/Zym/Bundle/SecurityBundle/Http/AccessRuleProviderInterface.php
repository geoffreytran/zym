<?php
namespace Zym\Bundle\SecurityBundle\Http;

use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface AccessRuleProviderInterface 
{
    /**
     * Get the access rules
     *
     * @return array Array of Zym\Bundle\SecurityBundle\Http\AccessRuleInterface
     */
    public function getRules();
}