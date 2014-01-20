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

namespace Zym\Security\Http\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

/**
 * Class AjaxAuthenticationSuccessHandler
 *
 * @package Zym\Security\Http\Authentication
 * @author Geoffrey Tran <geoffrey.tran@gmail.com>
 */
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