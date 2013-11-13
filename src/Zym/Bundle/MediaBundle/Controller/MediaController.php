<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
namespace Zym\Bundle\MediaBundle\Controller;

use Zym\Bundle\MediaBundle\Form;
use Zym\Bundle\MediaBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Zym\Bundle\MediaBundle\MediaPool;


/**
 * Media Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class MediaController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_media",
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

        /* @var $mediaManager Entity\MediaManager */
        $mediaManager  = $this->get('zym_media.media_manager');
        $medias        = $mediaManager->findMedias($filterBy, $page, $limit, $orderBy);

        return array(
            'medias' => $medias
        );
    }

    /**
     * @Route("/add", name="zym_media_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\MediaBundle\Entity\Media'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $media = new Entity\Media();
        $form = $this->createForm($this->get('zym_media.form.type.media'), $media, array(
            'provider' => 'zym_media.provider.image',
            'context'  => 'default'
        ));

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $mediaManager Entity\MediaManager */
                $mediaManager = $this->get('zym_media.media_manager');
                $mediaManager->createMedia($media);

                return $this->redirect($this->generateUrl('zym_media'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Edit a media
     *
     * @param Entity\Media $media
     *
     * @Route("/{id}/edit", name="zym_media_edit")
     * @ParamConverter("media", class="ZymMediaBundle:Media")
     * @Template()
     *
     * @SecureParam(name="media", permissions="EDIT")
     */
    public function editAction(Entity\Media $media)
    {
        $origMedia = clone $media;
        $form = $this->createForm($this->get('zym_media.form.type.media'), $media, array(
            'provider' => $media->getProviderName(),
            'context'  => $media->getContext()
        ));

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $mediaManager Entity\MediaManager */
                $mediaManager = $this->get('zym_media.media_manager');
                $mediaManager->saveMedia($media);

                return $this->redirect($this->generateUrl('zym_media'));
            }
        }

        return array(
            'media' => $origMedia,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a media
     *
     * @param Entity\Media $media
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_media_delete",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     *
     * @Template()
     *
     * @SecureParam(name="media", permissions="DELETE")
     */
    public function deleteAction(Entity\Media $media)
    {
        $origMedia = clone $media;

        /* @var $mediaManager Entity\MediaManager */
        $mediaManager = $this->get('zym_media.media_manager');
        $form        = $this->createForm(new Form\DeleteType(), $media);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $mediaManager->deleteMedia($media);

                return $this->redirect($this->generateUrl('zym_media'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $mediaManager->deleteMedia($media);

            return $this->redirect($this->generateUrl('zym_media'));
        }

        return array(
            'media' => $origMedia,
            'form' => $form->createView()
        );
    }

    /**
     * Show a media
     *
     * @param Entity\Media $media
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name     ="zym_media_show",
     *     defaults = { "_format" = "html" }
     * )
     * @ParamConverter("media", class="ZymMediaBundle:Media")
     * @Template()
     *
     * @SecureParam(name="media", permissions="VIEW")
     */
    public function showAction(Entity\Media $media)
    {
        $mediaPool = $this->get('zym_media.media_pool');
        $provider  = $mediaPool->getProvider($media->getProviderName());


        return array(
            'media'   => $media,
            'formats' => $provider->getFormats()
        );
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param string $id
     * @param string $format
     *
     * @return Response
     *
     * @ParamConverter("media", class="ZymMediaBundle:Media")
     * @SecureParam(name="media", permissions="VIEW")
     */
    public function viewAction(Entity\Media $media, $format = 'reference')
    {
        return $this->render('ZymMediaBundle:Media:view.html.twig', array(
            'media'     => $media,
            'formats'   => $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext()),
            'format'    => $format
        ));
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param Entity\Media $media
     * @param string $format
     *
     * @return Response
     *
     * @ParamConverter("media", class="ZymMediaBundle:Media")
     * @SecureParam(name="media", permissions="VIEW")
     */
    public function downloadAction(Entity\Media $media, $format = 'reference')
    {
        /** @var MediaPool  */
        $mediaPool = $this->get('zym_media.media_pool');
        $provider  = $mediaPool->getProvider($media->getProviderName());

        $response = $provider->getDownloadResponse($media, $format, $mediaPool->getDownloadMode($media));

        return $response;
    }
}