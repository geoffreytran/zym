<?php

namespace Zym\Security\Http\Authentication;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AjaxAuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    private $translator;

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernel
     * @param \Symfony\Component\Security\Http\HttpUtils        $httpUtils
     * @param array                                             $options
     * @param \Psr\Log\LoggerInterface                          $logger
     * @param type                                              $translator
     */
    public function __construct(HttpKernelInterface $httpKernel, HttpUtils $httpUtils, array $options, LoggerInterface $logger = null, $translator = null)
    {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);

        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() !== 'html') {
            $json = array(
                'code'    => 401,
                'message' => $this->translator->trans($exception->getMessage())
            );

            return new Response(json_encode($json), 401);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}