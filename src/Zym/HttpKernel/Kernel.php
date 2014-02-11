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

namespace Zym\HttpKernel;

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

// Xdebug issues with Symfony2 nesting levels
ini_set('xdebug.max_nesting_level', 200);

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Debug\Debug;

/**
 * Class Kernel
 *
 * @package Zym\HttpKernel
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
abstract class Kernel extends BaseKernel
{
    /**
     * Debugging environment
     */
    const ENVIRONMENT_DEBUG  = 'debug';

    /**
     * Development environment
     */
    const ENVIRONMENT_DEV    = 'dev';

    /**
     * Production environment
     */
    const ENVIRONMENT_PROD   = 'prod';

    /**
     * Testing environment
     */
    const ENVIORNMENT_TEST   = 'test';

    /**
     * Environments that have debugging enabled
     *
     * @var array
     */
    protected $debugEnvironments = array(
        self::ENVIRONMENT_DEBUG,
        self::ENVIRONMENT_DEV,
        self::ENVIORNMENT_TEST
    );

    /**
     * Construct
     *
     * @param string  $environment
     * @param boolean $debug
     *
     * @return void
     */
    public function __construct($environment, $debug = null)
    {
        if (empty($environment)) {
            throw new \InvalidArgumentException('Application environment is not set.');
        }
        
        // Normalize environment string
        $environment = strtolower($environment);

        // Determine Environment
        $environment = $this->determineEnvironment($environment);

        // Determine Debug
        $debug       = $this->determineDebug($debug, $environment);

        parent::__construct($environment, $debug);

        // Load the class cache
        if ($environment != self::ENVIRONMENT_DEBUG) {
            $this->loadClassCache();
        }
    }

    /**
     * Gets the cache directory.
     *
     * @return string The cache directory
     */
    public function getCacheDir()
    {
        return $this->getDataDir() . '/cache/' . $this->environment;
    }

    /**
     * Gets the log directory.
     *
     * @return string The log directory
     */
    public function getLogDir()
    {
        return $this->getDataDir() . '/logs';
    }

    /**
     * Gets the data directory
     *
     * @return string the data directory
     */
    public function getDataDir()
    {
        return $this->rootDir . '/../data';
    }

    /**
     * Returns the kernel parameters.
     *
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters()
    {
        $params = array_merge(
            parent::getKernelParameters(),
            array(
                'kernel.data_dir' => $this->getDataDir()
            )
        );

        return $params;
    }

    /**
     * Determine the environment
     *
     * @param string $environment
     * @return string
     */
    private function determineEnvironment($environment)
    {
        if (in_array($environment, $this->debugEnvironments)
            && isset($_SERVER['REQUEST_URI'])
            && preg_match('/^\/app_([a-zA-Z0-9]+)\.php/', $_SERVER['REQUEST_URI'], $environmentMatch)) {
            // Set url forced environment (/app_prod.php, /app_dev.php)
            $environment = $environmentMatch[1];

            // Fix $_SERVER vars
            $_SERVER['SCRIPT_NAME']     = str_replace('/' . basename($_SERVER['SCRIPT_NAME']),
                                                      $environmentMatch[0],
                                                      $_SERVER['SCRIPT_NAME']);

            $_SERVER['SCRIPT_FILENAME'] = str_replace('/' . basename($_SERVER['SCRIPT_FILENAME']),
                                                      $environmentMatch[0],
                                                      $_SERVER['SCRIPT_FILENAME']);

            $_SERVER['PHP_SELF']        = str_replace('/' . basename($_SERVER['PHP_SELF']),
                                                      $environmentMatch[0],
                                                      $_SERVER['PHP_SELF']);
        }

        return $environment;
    }

    /**
     * Determine the debug setting
     *
     * @param boolean $debug
     * @param string  $environment
     * @return boolean
     */
    private function determineDebug($debug, $environment)
    {
        // Enable debug for supported environments by default
        if ($debug === null && in_array($environment, $this->debugEnvironments)) {
            $debug = true;
        } else if ($debug === null) {
            $debug = false;
        }

        return $debug;
    }
}