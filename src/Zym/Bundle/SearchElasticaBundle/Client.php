<?php

namespace Zym\Bundle\SearchElasticaBundle;

class Client extends \Elastica_Client
{
    protected $logger;

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function request($path, $method, $data = array(), array $query = array())
    {
        $start = microtime(true);
        $response = parent::request($path, $method, $data, $query);

        if (null !== $this->logger) {
            $time = microtime(true) - $start;
            $this->logger->logQuery($path, $method, $data, $time);
        }

        return $response;
    }
}