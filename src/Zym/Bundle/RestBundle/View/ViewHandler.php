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

namespace Zym\Bundle\RestBundle\View;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler as BaseViewHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ViewHandler extends BaseViewHandler
{
    public function createRedirectResponse(View $view, $location, $format)
    {
        if ($format == 'html' && $view->getData() != null && ($view->getStatusCode() == Codes::HTTP_CREATED || $view->getStatusCode() == Codes::HTTP_ACCEPTED)) {
            $prevStatus = $view->getStatusCode();
            $view->setStatusCode(Codes::HTTP_OK);
        }

        $response = parent::createRedirectResponse($view, $location, $format);

        if ($response->headers->has('Location') && $response->getStatusCode() !== Codes::HTTP_CREATED && ($response->getStatusCode() < 300 || $response->getStatusCode() >= 400)) {
            $response->headers->remove('Location');
        }

        if (isset($prevStatus)) {
            $view->setStatusCode($prevStatus);
            $code = isset($this->forceRedirects[$format])
                        ? $this->forceRedirects[$format] : $this->getStatusCode($view, $response->getContent());

            $response->setStatusCode($code);
        }
        return $response;
    }

} 