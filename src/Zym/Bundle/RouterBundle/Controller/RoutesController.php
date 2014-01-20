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

namespace Zym\Bundle\RouterBundle\Controller;

use Zym\Bundle\RouterBundle\Form;
use Zym\Bundle\RouterBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Routes Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RoutesController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_router_routes",
     *     defaults = { "_format" = "html" }
     * )
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        /* @var $routeManager Entity\RouteManager */
        $routeManager  = $this->get('zym_router.route_manager');
        $routes        = $routeManager->findRoutes($filterBy, $page, $limit, $orderBy);

        return array(
            'routes' => $routes
        );
    }

    /**
     * @Route("/add", name="zym_router_routes_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\RouterBundle\Entity\Route'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $route = new Entity\Route(null, null);
        $form = $this->createForm(new Form\RouteType, $route);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $routeManager Entity\RouteManager */
                $routeManager = $this->get('zym_router.route_manager');
                $routeManager->createRoute($route);

                return $this->redirect($this->generateUrl('zym_router_routes'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Edit a route
     *
     * @param Entity\Route $route
     *
     * @Route("/{name}/edit", name="zym_router_routes_edit")
     * @ParamConverter("route", class="ZymRouterBundle:Route")
     * @Template()
     *
     * @SecureParam(name="route", permissions="EDIT")
     */
    public function editAction(Entity\Route $route)
    {
        $origRoute = clone $route;
        $form = $this->createForm(new Form\RouteType, $route);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $routeManager Entity\RouteManager */
                $routeManager = $this->get('zym_router.route_manager');
                $routeManager->saveRoute($route);

                return $this->redirect($this->generateUrl('zym_router_routes'));
            }
        }

        return array(
            'route' => $origRoute,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a route
     *
     * @param Entity\Route $route
     *
     * @Route(
     *     "/{name}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{name}/delete.{_format}",
     *     name="zym_router_routes_delete",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     *
     * @Template()
     *
     * @SecureParam(name="route", permissions="DELETE")
     */
    public function deleteAction(Entity\Route $route)
    {
        $origRoute = clone $route;

        /* @var $routeManager Entity\RouteManager */
        $routeManager = $this->get('zym_router.route_manager');
        $form        = $this->createForm(new Form\DeleteType(), $route);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $routeManager->deleteRoute($route);

                return $this->redirect($this->generateUrl('zym_router_routes'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $routeManager->deleteRoute($route);

            return $this->redirect($this->generateUrl('zym_router_routes'));
        }

        return array(
            'route' => $origRoute,
            'form' => $form->createView()
        );
    }

    /**
     * Show a route
     *
     * @param Entity\Route $route
     *
     * @Route(
     *     "/{name}.{_format}",
     *     name     ="zym_router_routes_show",
     *     defaults = { "_format" = "html" }
     * )
     * @ParamConverter("route", class="ZymRouterBundle:Route")
     * @Template()
     *
     * @SecureParam(name="route", permissions="VIEW")
     */
    public function showAction(Entity\Route $route)
    {
        return array(
            'route'   => $route,
        );
    }
}