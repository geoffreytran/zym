<?php

namespace Zym\Bundle\MediaBundle\Provider;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\AbstractType as FormAbstractType;

interface MediaProviderInterface
{
    /**
     * @param string $name
     * @param array  $format
     *
     * @return void
     */
    public function addFormat($name, $format);

    /**
     * return the format settings
     *
     * @param string $name
     *
     * @return array|false the format settings
     */
    public function getFormat($name);

    /**
     *
     * @return array
     */
    public function getFormats();

    /**
     * return true if the media related to the provider required thumbnails (generation)
     *
     * @return boolean
     */
    public function requireThumbnails();

    /**
     * Generated thumbnails linked to the media, a thumbnail is a format used on the website
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function generateThumbnails(MediaInterface $media);

    /**
     * remove all linked thumbnails
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function removeThumbnails(MediaInterface $media);

    /**
     * @param MediaInterface $media
     *
     * @return \Gaufrette\File
     */
    public function getReferenceFile(MediaInterface $media);

    /**
     * return the reference image of the media, can be the video thumbnail or the original uploaded picture
     *
     * @param MediaInterface $media
     *
     * @return string to the reference image
     */
    public function getReferenceImage(MediaInterface $media);

    /**
     * return the correct format name : providerName_format
     *
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return string
     */
    public function getFormatName(MediaInterface $media, $format);

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function prePersist(MediaInterface $media);

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postPersist(MediaInterface $media);

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preUpdate(MediaInterface $media);

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postUpdate(MediaInterface $media);

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preRemove(MediaInterface $media);

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postRemove(MediaInterface $media);

    /**
     * build the related create form
     *
     * @return FormAbstractType
     */
    public function getCreateForm();

    /**
     * build the related create form
     *
     * @return FormAbstractType
     */
    public function getEditForm();

    /**
     * @param MediaInterface $media
     * @param string         $format
     */
    public function getHelperProperties(MediaInterface $media, $format);

    /**
     * Generate the media path
     *
     * @param MediaInterface $media
     *
     * @return string
     */
    public function generatePath(MediaInterface $media);

    /**
     * Generate the public path
     *
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return string
     */
    public function generatePublicUrl(MediaInterface $media, $format);

    /**
     * Generate the private path
     *
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return string
     */
    public function generatePrivateUrl(MediaInterface $media, $format);

    /**
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     *
     * @param array $templates
     */
    public function setTemplates(array $templates);

    /**
     *
     * @return array
     */
    public function getTemplates();

    /**
     * @param string $name
     *
     * @return string
     */
    public function getTemplate($name);

    /**
     * Mode can be x-file
     *
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $mode
     * @param array          $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array());

    /**
     * @return ResizerInterface
     */
    public function getResizer();

    /**
     * @return Filesystem
     */
    public function getFilesystem();

    /**
     * @param string $relativePath
     * @param bool   $isFlushable
     */
    public function getCdnPath($relativePath, $isFlushable);

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function transform(MediaInterface $media);

    /**
     * @param ErrorElement     $errorElement
     * @param MediaInterface   $media
     *
     * @return void
     */
    public function validate(ErrorElement $errorElement, MediaInterface $media);

    /**
     * @param MediaInterface $media
     * @param bool           $force
     *
     * @return void
     */
    public function updateMetadata(MediaInterface $media, $force = false);
}