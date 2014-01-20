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

namespace Zym\Bundle\SearchBundle\Event;

use Zym\Search;
use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Search Query pagination.
 *
 */
class QuerySubscriber implements EventSubscriberInterface
{
    /**
     * Search Index Manager
     *
     * @var Search\IndexManagerInterface
     */
    private $indexManager;

    public function __construct(Search\IndexManagerInterface $indexManager)
    {
        $this->indexManager = $indexManager;
    }

    protected function getIndexManager()
    {
        return $this->indexManager;
    }

    protected function setIndexManager(Search\IndexManagerInterface $indexManager)
    {
        $this->indexManager = $indexManager;
    }

    public function items(ItemsEvent $event)
    {
        if ($event->target instanceof Search\QueryInterface) {
            $searchIndex = $this->getIndexManager();
            $query       = $event->target;

            $query->setFrom($event->getOffset());
            $query->setLimit($event->getLimit());

            $results = $searchIndex->search($query);

            $event->count = $results->getTotalHits();

            if ($results->hasFacets()) {
                $event->setCustomPaginationParameter('facets', $results->getFacets());
            }

            $event->items = $results->getResults();
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 0) /* triggers before a standard array subscriber*/
        );
    }
}