<?php

namespace Zym\Security\Http\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

class AjaxAuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() !== 'html') {
            $json = array(
                'username'    => $token->getUsername(),
                'redirectUrl' => $this->determineTargetUrl($request)
            );

            return new Response(json_encode($json));
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}