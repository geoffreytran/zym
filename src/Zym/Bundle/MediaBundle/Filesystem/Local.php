<?php

namespace Zym\Bundle\MediaBundle\Filesystem;

use Gaufrette\Adapter\Local as BaseLocal;

class Local extends BaseLocal
{
    public function getDirectory()
    {
        return $this->directory;
    }
}