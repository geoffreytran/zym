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

namespace Zym\Bundle\ContentBundle\Controller;

use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use FOS\RestBundle\EventListener\ViewResponseListener;
use FOS\RestBundle\View\View as ViewResponse;
use FOS\RestBundle\Util\Codes;
use Knp\Component\Pager\Paginator;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;
use Symfony\Component\HttpFoundation\Request;
use Zym\Bundle\ContentBundle\Form;
use Zym\Bundle\ContentBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Pages Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class PagesController extends Controller
{
    /**
     * List all pages
     *
     * @Route(
     *     ".{_format}",
     *     name="zym_content_pages",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET"}
     * )
     * @View()
     *
     * @ApiDoc(
     *     description="Returns a list pages",
     *     section="Pages",
     *     filters={
     *         {"name"="page", "dataType"="integer"},
     *         {"name"="limit", "dataType"="integer"},
     *         {"name"="orderBy", "dataType"="array"},
     *         {"name"="filterBy", "dataType"="array"}
     *     }
     * )
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        /** @var $documentManager DocumentManager */
        $documentManager = $this->get('doctrine_phpcr.odm.default_document_manager');
        $qb = $documentManager->createQueryBuilder();
        $qb->from()->document('Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page', 'p');
        $query = $qb->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pages = $paginator->paginate(
                                $query->execute(),
                                    $this->get('request')->query->get('page', 1)/*page number*/,
                                    2
        );


        return array(
            'pages' => $pages
        );
    }

    /**
     * Add a page
     *
     * @Route(
     *     "/add.{_format}",
     *     name="zym_content_pages_add",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     ".{_format}",
     *     name="zym_content_pages_post_add",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"POST"}
     * )
     *
     * @View()
     * @ApiDoc(
     *     description="Add a page",
     *     section="Pages",
     *     parameters={
     *         {"name"="zym_content_page[email]", "dataType"="string", "required"=true, "description"="Email address", "readonly"=false},
     *         {"name"="zym_content_page[plainPassword][password]", "dataType"="string", "required"=true, "description"="Password", "readonly"=false},
     *         {"name"="zym_content_page[plainPassword][confirmPassword]", "dataType"="string", "required"=true, "description"="Confirm Password", "readonly"=false}
     *     }
     * )
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // Check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page'))) {
            //throw new AccessDeniedException();
        }

        $page = new Page();
        $page->setParent(new Generic());

        $form = $this->createForm(new Form\PageType(), $page);

        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $documentManager DocumentManager */
            $documentManager = $this->get('doctrine_phpcr.odm.default_document_manager');
            $documentManager->persist($page);
            $documentManager->flush();

//            return ViewResponse::createRouteRedirect(
//                                   'zym_content_pages_show',
//                                   array(
//                                       'id'      => $page->getId(),
//                                       '_format' => $request->getRequestFormat()
//                                   ),
//                                   Codes::HTTP_CREATED
//                               )
//                               ->setData(array(
//                                   'page' => $page
//                               ))
//            ;
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Show a page
     *
     * @param Entity\Page $page
     *
     * @return array
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_content_pages_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"GET"}
     * )
     * @ParamConverter("page", class="ZymContentBundle:Page")
     * @View()
     *
     * @SecureParam(name="page", permissions="VIEW")
     *
     * @ApiDoc(
     *     description="Returns a page",
     *     section="Pages"
     * )
     */
    public function showAction(Entity\Page $page)
    {
        return array('page' => $page);
    }

    /**
     * Edit a page
     *
     * @param Entity\Page $page
     *
     * @return array
     *
     * @Route(
     *     "/{id}/edit.{_format}",
     *     name="zym_content_pages_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_content_pages_put_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"PUT"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_content_pages_patch_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"PATCH"}
     * )
     *
     * @ParamConverter("page", class="ZymContentBundle:Page")
     * @View()
     *
     * @SecureParam(name="page", permissions="EDIT")
     *
     * @ApiDoc(
     *     description="Edit a page",
     *     section="Pages",
     *     parameters={
     *         {"name"="zym_content_page[email]", "dataType"="string", "required"=true, "description"="Email address", "readonly"=false},
     *         {"name"="zym_content_page[plainPassword][password]", "dataType"="string", "required"=true, "description"="Password", "readonly"=false},
     *         {"name"="zym_content_page[plainPassword][confirmPassword]", "dataType"="string", "required"=true, "description"="Confirm Password", "readonly"=false}
     *     }
     * )
     */
    public function editAction(Entity\Page $page)
    {
        $request = $this->getRequest();

        $originalPage = clone $page;
        $form         = $this->createForm(new Form\PageType(), $page, array(
            'method' => in_array($request->getMethod(), array('PUT', 'PATCH')) ? $request->getMethod() : 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $pageManager \Zym\Bundle\ContentBundle\Entity\PageManager */
            $pageManager = $this->get('fos_page.page_manager');
            $pageManager->savePage($page);

            return ViewResponse::createRouteRedirect(
                               'zym_content_pages_show',
                                   array('id' => $page->getId()),
                                   Codes::HTTP_OK
            )
                               ->setData(array(
                    'page' => $page
                ));
        }

        return array(
            'page' => $originalPage,
            'form' => $form
        );
    }

    /**
     * Delete a page
     *
     * @param Entity\Page $page
     *
     * @return array
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_content_pages_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_content_pages_delete_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = ".+"
     *     },
     *     methods={"DELETE"}
     * )
     *
     * @SecureParam(name="page", permissions="DELETE")
     *
     * @View()
     * @ApiDoc(
     *     description="Delete a page",
     *     section="Pages"
     * )
     */
    public function deleteAction(Entity\Page $page)
    {
        $request = $this->getRequest();

        $form = $this->createForm(new Form\DeleteType(), $page, array(
            'method' => in_array($request->getMethod(), array('DELETE')) ? $request->getMethod() : 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isValid() || $request->getMethod() == 'DELETE') {
            /* @var $pageManager Entity\PageManager */
            $pageManager = $this->get('fos_page.page_manager');
            $pageManager->deletePage($page);

            return ViewResponse::createRouteRedirect('zym_content_pages', array(), Codes::HTTP_OK);
        }

        return array(
            'page' => $page,
            'form' => $form
        );
    }
}
