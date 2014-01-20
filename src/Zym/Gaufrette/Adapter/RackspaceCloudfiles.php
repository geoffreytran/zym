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

namespace Zym\Gaufrette\Adapter;

use Gaufrette\Adapter\RackspaceCloudfiles as BaseRackspaceCloudfiles;

/**
 * Class RackspaceCloudfiles
 *
 * @package Zym\Gaufrette\Adapter
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
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