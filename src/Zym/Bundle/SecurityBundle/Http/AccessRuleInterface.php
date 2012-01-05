<?php
namespace Zym\Bundle\SecurityBundle\Http;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;

interface AccessRuleInterface 
{
    /**
     * Get the request matcher
     *
     * @return RequestMatcher
     */
    public function getRequestMatcher();
    
    /**
     * Get the roles
     *
     * @return array
     */
    public function getRoles();
    
    /**
     * Get the required channel
     *
     * @return string
     */
    public function getChannel();
}