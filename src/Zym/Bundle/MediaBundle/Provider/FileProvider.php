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

namespace Zym\Bundle\MediaBundle\Provider;

use Gaufrette\Filesystem;
use Zym\Bundle\MediaBundle\CDN\CDNInterface;
use Zym\Bundle\MediaBundle\Generator\GeneratorInterface;
use Zym\Bundle\MediaBundle\Thumbnail\ThumbnailInterface;
use Zym\Bundle\MediaBundle\Model\MediaInterface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\FormBuilderInterface;

class FileProvider extends AbstractProvider
{
    /**
     * Templates
     *
     * @var array
     */
    protected $templates = array(
        'thumbnail' => 'ZymMediaBundle:Provider:thumbnail.html.twig',
        'view'      => 'ZymMediaBundle:Provider:viewFile.html.twig'
    );

    protected $allowedExtensions;

    protected $allowedMimeTypes;

    /**
     * @param string                                           $name
     * @param \Gaufrette\Filesystem                            $filesystem
     * @param \Sonata\MediaBundle\CDN\CDNInterface             $cdn
     * @param \Sonata\MediaBundle\Generator\GeneratorInterface $pathGenerator
     * @param \Sonata\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     * @param array                                            $allowedExtensions
     * @param array                                            $allowedMimeTypes
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array())
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->allowedExtensions = $allowedExtensions;
        $this->allowedMimeTypes  = $allowedMimeTypes;
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);
        $this->fixFilename($media);

        // this is the name used to store the file
        if (!$media->getProviderReference()) {
            $media->setProviderReference($this->generateReferenceName($media));
        }

        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceFile(MediaInterface $media)
    {
        return $this->getFilesystem()->get($this->getReferenceImage($media), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }

    /**
     * build the related create form
     *
     * @return FormAbstractType
     */
    public function getCreateForm()
    {
    }

    /**
     * build the related create form
     *
     * @return FormAbstractType
     */
    public function getEditForm()
    {

    }

    public function buildMediaType(FormBuilderInterface $builder)
    {
        $builder->add('binaryContent', 'file', array(
            'label' => 'File'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        return array_merge(array(
            'title'       => $media->getName(),
            'thumbnail'   => $this->getReferenceImage($media),
            'file'        => $this->generatePublicUrl($media, $format),
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = sprintf('media_bundle/images/files/%s/file.png', $format);
            //$path = $path = $this->thumbnail->generatePublicUrl($this, $media, $format);;
        }

        try {
            return $this->getCdn()->getPath($path, $media->isCdnFlushable());
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array())
    {
        // build the default headers
        $headers = array_merge(array(
            'Content-Type'          => $media->getContentType(),
            //'Content-Disposition'   => sprintf('attachment; filename="%s"', $media->getMetadataValue('filename')),
        ), $headers);

        if (!in_array($mode, array('http', 'X-Sendfile', 'X-Accel-Redirect'))) {
            throw new \RuntimeException('Invalid mode provided');
        }

        if ($mode == 'http') {
            $provider = $this;

            return new StreamedResponse(function() use ($provider, $media, $format) {
                if ($format == 'reference') {
                    echo $provider->getReferenceFile($media)->getContent();
                } else {
                    echo $provider->getFilesystem()->get($provider->generatePrivateUrl($media, $format), true)->getContent();
                }
            }, 200, $headers);
        }

        if (!$this->getFilesystem()->getAdapter() instanceof \Zym\Bundle\MediaBundle\Filesystem\Local) {
            throw new \RuntimeException('Cannot use X-Sendfile or X-Accel-Redirect with non \Zym\Bundle\MediaBundle\Filesystem\Local');
        }

        $headers[$mode] = sprintf('%s/%s',
            $this->getFilesystem()->getAdapter()->getDirectory(),
            $this->generatePrivateUrl($media, $format)
        );

        return new Response('', 200, $headers);
    }

    /**
     * @param ErrorElement     $errorElement
     * @param MediaInterface   $media
     *
     * @return void
     */
    public function validate(ErrorElement $errorElement, MediaInterface $media)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        // this is now optimized at all!!!
        $path = tempnam(sys_get_temp_dir(), 'zym_media_update_metadata');

        $fileObject = new \SplFileObject($path, 'w');
        $fileObject->fwrite($this->getReferenceFile($media)->getContent());

        $media->setSize($fileObject->getSize());
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

        $this->setFileContents($media);

        //$this->generateThumbnails($media);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(MediaInterface $media)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

        // Delete the current file from the FS
        $oldMedia = clone $media;
        $oldMedia->setProviderReference($media->getPreviousProviderReference());

        $path = $this->getReferenceImage($oldMedia);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->fixBinaryContent($media);

        $this->setFileContents($media);

        $this->generateThumbnails($media);
    }

    /**
     * @throws \RuntimeException
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

        // if the binary content is a filename => convert to a valid File
        if (!$media->getBinaryContent() instanceof File) {
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : ' . $media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());

            $media->setBinaryContent($binaryContent);
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    protected function fixFilename(MediaInterface $media)
    {
        if ($media->getBinaryContent() instanceof UploadedFile) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getClientOriginalName());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getClientOriginalName());
        } else if ($media->getBinaryContent() instanceof File) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getBasename());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getBasename());
        }

        // this is the original name
        if (!$media->getName()) {
            throw new \RuntimeException('Please define a valid media\'s name');
        }
    }

    /**
     * Set the file contents for an image
     *
     * @param MediaInterface $media
     * @param string         $contents path to contents, defaults to MediaInterface BinaryContent
     *
     * @return void
     */
    protected function setFileContents(MediaInterface $media, $contents = null)
    {
        $file = $this->getFilesystem()->get(sprintf('%s/%s', $this->generatePath($media), $media->getProviderReference()), true);

        if (!$contents) {
            $contents = $media->getBinaryContent()->getRealPath();
        }

        $file->setContent(file_get_contents($contents));
    }

    /**
     * @param MediaInterface $media
     *
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        return sha1($media->getName() . rand(11111, 99999)) . '.' . $media->getBinaryContent()->guessExtension();
    }
}