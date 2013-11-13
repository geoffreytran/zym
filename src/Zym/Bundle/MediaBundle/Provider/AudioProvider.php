<?php

namespace Zym\Bundle\MediaBundle\Provider;

use Gaufrette\Filesystem;
use Zym\Bundle\MediaBundle\CDN\CDNInterface;
use Zym\Bundle\MediaBundle\Generator\GeneratorInterface;
use Zym\Bundle\MediaBundle\Thumbnail\ThumbnailInterface;
use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Imagine\Image\ImagineInterface;

use Symfony\Component\Form\FormBuilderInterface;

class AudioProvider extends FileProvider
{
    /**
     * Templates
     *
     * @var array
     */
    protected $templates = array(
        'thumbnail' => 'ZymMediaBundle:Provider:thumbnail.html.twig',
        'view'      => 'ZymMediaBundle:Provider:viewAudio.html.twig'
    );

    protected $imagineAdapter;

    /**
     * @param string                                           $name
     * @param \Gaufrette\Filesystem                            $filesystem
     * @param CDNInterface                                     $cdn
     * @param GeneratorInterface                               $pathGenerator
     * @param ThumbnailInterface                               $thumbnail
     * @param array                                            $allowedExtensions
     * @param array                                            $allowedMimeTypes
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array())
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes);
    }


    public function buildMediaType(FormBuilderInterface $builder)
    {
        $builder->add('binaryContent', 'file', array(
            'label' => 'Audio File'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        $sources = array();
        foreach ($this->getFormats() as $frmt => $frmtOptions) {
            if ($frmt == 'admin') {
                continue;
            }

            $sources[] = array_merge_recursive(array(
                'src' => $this->generatePublicUrl($media, $frmt)
            ), $frmtOptions);
        }

        return array_merge(array(
            'src'        => $this->generatePublicUrl($media, $format),
            'sources'    => $sources
        ), $options);
    }

}