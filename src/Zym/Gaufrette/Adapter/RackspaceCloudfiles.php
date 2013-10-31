<?php

namespace Zym\Gaufrette\Adapter;

use Gaufrette\Adapter\RackspaceCloudfiles as BaseRackspaceCloudfiles;

class RackspaceCloudfiles extends BaseRackspaceCloudfiles
{
    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        /* @var $object \CF_Object */
        $object = $this->tryGetObject($key);
        if (false === $object) {
            // the object does not exist, so we create it
            $object = $this->container->create_object($key);
        }

        if (!$object->content_type) {
            $object->content_type = 'application/octet-stream';
        }

        if (!$object->write($content, 0, false)) {
            return false;
        }

        return $object->content_length;
    }
}