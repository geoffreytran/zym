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

namespace Zym\Bundle\SearchElasticaBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Logger for the Elastica.
 *
 * The {@link logQuery()} method is configured as the logger callable in the
 * service container.
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class ElasticaLogger
{
    protected $logger;
    protected $queries;
    protected $debug;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger The Symfony logger
     */
    public function __construct(LoggerInterface $logger = null, $debug = false)
    {
        $this->logger = $logger;
        $this->queries = array();
        $this->debug = $debug;
    }

    /**
     * Logs a query.
     *
     * @param string $path   Path to call
     * @param string $method Rest method to use (GET, POST, DELETE, PUT)
     * @param array  $data   arguments
     * @param float  $time   execution time
     */
    public function logQuery($path, $method, $data, $time)
    {
        if ($this->debug) {
            $this->queries[] = array(
                'path' => $path,
                'method' => $method,
                'data' => $data,
                'executionMS' => $time
            );
        }

        if (null !== $this->logger) {
            $message = sprintf("%s (%s) %0.2f ms", $path, $method, $time * 1000);
            $this->logger->info($message, (array) $data);
        }
    }

    /**
     * Returns the number of queries that have been logged.
     *
     * @return integer The number of queries logged
     */
    public function getNbQueries()
    {
        return count($this->queries);
    }

    /**
     * Returns a human-readable array of queries logged.
     *
     * @return array An array of queries
     */
    public function getQueries()
    {
        return $this->queries;
    }
}