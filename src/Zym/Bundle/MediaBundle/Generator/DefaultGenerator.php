<?php

namespace Zym\Bundle\MediaBundle\Generator;

use Zym\Bundle\MediaBundle\Model\MediaInterface;

class DefaultGenerator implements GeneratorInterface
{
    protected $firstLevel;
    protected $secondLevel;

    /**
     * Construct
     *
     * @param int $firstLevel
     * @param int $secondLevel
     */
    public function __construct($firstLevel = 100000, $secondLevel = 1000)
    {
        $this->firstLevel = $firstLevel;
        $this->secondLevel = $secondLevel;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {
        $repFirstLevel  = (int) ($media->getId() / $this->firstLevel);
        $repSecondLevel = (int) (($media->getId() - ($repFirstLevel * $this->firstLevel)) / $this->secondLevel);

        return sprintf('%s/%04s/%02s', $media->getContext(), $repFirstLevel + 1, $repSecondLevel + 1);
    }
}