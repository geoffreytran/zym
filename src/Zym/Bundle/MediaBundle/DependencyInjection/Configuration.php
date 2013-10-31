<?php

namespace Zym\Bundle\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('zym_media');

        $node
            ->children()
                ->scalarNode('db_driver')->isRequired()->end()
                ->scalarNode('default_context')->defaultValue('default')->end()
            ->end()
        ;

        $this->addContextsSection($node);
        $this->addCdnSection($node);
        $this->addFilesystemSection($node);
        $this->addProvidersSection($node);
        $this->addExtraSection($node);
        $this->addModelSection($node);
        $this->addBuzzSection($node);
        $this->addResizerSection($node);

        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addContextsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('contexts')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('download')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('strategy')->defaultValue('service_container')->end()
                                    ->scalarNode('mode')->defaultValue('http')->end()
                                ->end()
                            ->end()
                            ->arrayNode('providers')
                                ->prototype('scalar')
                                    ->defaultValue(array())
                                ->end()
                            ->end()
                            ->arrayNode('formats')
                                ->isRequired()
                                ->useAttributeAsKey('id')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('width')->defaultValue(false)->end()
                                        ->scalarNode('height')->defaultValue(false)->end()
                                        ->scalarNode('quality')->defaultValue(80)->end()
                                        ->scalarNode('format')->defaultValue('jpg')->end()
                                        ->scalarNode('constraint')->defaultValue(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addCdnSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('cdn')
                    ->children()
                        ->arrayNode('server')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('path')->defaultValue('/uploads/media')->end()
                            ->end()
                        ->end()

                        ->arrayNode('fallback')
                            ->children()
                                ->scalarNode('master')->isRequired()->end()
                                ->scalarNode('fallback')->isRequired()->end()
                            ->end()
                        ->end()

                        ->arrayNode('rackspace_cloudfiles')
                            ->children()
                                ->scalarNode('service_id')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addFilesystemSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('filesystem')
                    ->children()
                        ->arrayNode('local')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('directory')->defaultValue('%kernel.root_dir%/../web/uploads/media')->end()
                                ->scalarNode('create')->defaultValue(false)->end()
                            ->end()
                        ->end()

                        ->arrayNode('ftp')
                            ->children()
                                ->scalarNode('directory')->isRequired()->end()
                                ->scalarNode('host')->isRequired()->end()
                                ->scalarNode('username')->isRequired()->end()
                                ->scalarNode('password')->isRequired()->end()
                                ->scalarNode('port')->defaultValue(21)->end()
                                ->scalarNode('passive')->defaultValue(false)->end()
                                ->scalarNode('create')->defaultValue(false)->end()
                            ->end()
                        ->end()

                        ->arrayNode('s3')
                            ->children()
                                ->scalarNode('directory')->defaultValue('')->end()
                                ->scalarNode('bucket')->isRequired()->end()
                                ->scalarNode('accessKey')->isRequired()->end()
                                ->scalarNode('secretKey')->isRequired()->end()
                                ->scalarNode('create')->defaultValue(false)->end()
                            ->end()
                        ->end()

                        ->arrayNode('rackspace_cloudfiles')
                            ->children()
                                ->scalarNode('service_id')->end()
                            ->end()
                        ->end()

                        ->arrayNode('mogilefs')
                            ->children()
                                ->scalarNode('domain')->isRequired()->end()
                                ->arrayNode('hosts')
                                    ->prototype('scalar')->end()
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('replicate')
                            ->children()
                                ->scalarNode('master')->isRequired()->end()
                                ->scalarNode('slave')->isRequired()->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addProvidersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('file')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('zym_media.provider.file')->end()
                                ->scalarNode('resizer')->defaultValue(false)->end()
                                ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                                ->arrayNode('allowed_extensions')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array(
                                        'pdf', 'txt', 'rtf',
                                        'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pttx',
                                        'odt', 'odg', 'odp', 'ods', 'odc', 'odf', 'odb',
                                        'csv',
                                        'xml',
                                    ))
                                ->end()
                                ->arrayNode('allowed_mime_types')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array(
                                        'application/pdf', 'application/x-pdf', 'application/rtf', 'text/html', 'text/rtf', 'text/plain',
                                        'application/excel', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint',
                                        'application/vnd.ms-powerpoint', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.graphics', 'application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.chart', 'application/vnd.oasis.opendocument.formula', 'application/vnd.oasis.opendocument.database', 'application/vnd.oasis.opendocument.image',
                                        'text/comma-separated-values',
                                        'text/xml', 'application/xml',
                                        'application/zip', // seems to be used for xlsx document ...
                                    ))
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('zym_media.provider.image')->end()
                                ->scalarNode('resizer')->defaultValue('zym_media.resizer.simple_image')->end()
                                ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                                ->scalarNode('adapter')->defaultValue('zym_media.adapter.image.gd')->end()
                                ->arrayNode('allowed_extensions')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('jpg', 'png', 'jpeg'))
                                ->end()
                                ->arrayNode('allowed_mime_types')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array(
                                        'image/pjpeg',
                                        'image/jpeg',
                                        'image/png',
                                        'image/x-png',
                                    ))
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('audio')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('service')->defaultValue('zym_media.provider.audio')->end()
                                    ->scalarNode('resizer')->defaultValue('zym_media.resizer.simple_image')->end()
                                    ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                    ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                    ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                    ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                                    ->scalarNode('adapter')->defaultValue('zym_media.adapter.image.gd')->end()
                                    ->arrayNode('allowed_extensions')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('aac', 'mp4', 'm4a', 'mp1', 'mp2', 'mp3', 'mpg', 'mpeg', 'oga', 'ogg', 'wav', 'webm'))
                                ->end()
                                ->arrayNode('allowed_mime_types')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array(
                                        'audio/aac',
                                        'audio/mp4',
                                        'audio/mpeg',
                                        'audio/ogg',
                                        'audio/wav',
                                        'audio/webm'
                                    ))

                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('youtube')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('zym_media.provider.youtube')->end()
                                ->scalarNode('resizer')->defaultValue('zym_media.resizer.simple_image')->end()
                                ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                            ->end()
                        ->end()

                        ->arrayNode('dailymotion')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('zym_media.provider.dailymotion')->end()
                                ->scalarNode('resizer')->defaultValue('zym_media.resizer.simple_image')->end()
                                ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                            ->end()
                        ->end()

                        ->arrayNode('vimeo')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('zym_media.provider.vimeo')->end()
                                ->scalarNode('resizer')->defaultValue('zym_media.resizer.simple_image')->end()
                                ->scalarNode('filesystem')->defaultValue('zym_media.filesystem.local')->end()
                                ->scalarNode('cdn')->defaultValue('zym_media.cdn.server')->end()
                                ->scalarNode('generator')->defaultValue('zym_media.generator.default')->end()
                                ->scalarNode('thumbnail')->defaultValue('zym_media.thumbnail.format')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addExtraSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pixlr')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enabled')->defaultValue(false)->end()
                        ->scalarNode('secret')->defaultValue(sha1(uniqid(rand(1, 9999), true)))->end()
                        ->scalarNode('referrer')->defaultValue('Sonata Media')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addModelSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('media')->defaultValue('Zym\\Bundle\\MediaBundle\\Entity\\Media')->end()
                        ->scalarNode('gallery')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\Gallery')->end()
                        ->scalarNode('gallery_has_media')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\GalleryHasMedia')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBuzzSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('buzz')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client')->defaultValue('zym_media.buzz.client.file_get_contents')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addResizerSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('simple_image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('mode')->defaultValue('inset')->end()
                            ->end()
                        ->end()
                        ->arrayNode('square_image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('mode')->defaultValue('inset')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}