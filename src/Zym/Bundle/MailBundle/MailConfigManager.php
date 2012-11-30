<?php

namespace Zym\Bundle\MailBundle;

use Zym\Bundle\RuntimeConfigBundle\Entity\Parameter;
use Zym\Bundle\RuntimeConfigBundle\Entity\ParameterManager;

class MailConfigManager
{
    /**
     * Parameter Manager
     *
     * @var ParameterManager
     */
    private $parameterManager;

    /**
     * Construct
     *
     * @param ParameterManager $parameterManager
     */
    public function __construct(ParameterManager $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    public function loadMailConfig()
    {
        $parameterManager = $this->getParameterManager();
        $config           = new MailConfig;

        $parameter = $parameterManager->findParameter('mail.transport');
        if ($parameter) {
            $config->setTransport($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.host');
        if ($parameter) {
            $config->setHost($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.port');
        if ($parameter) {
            $config->setPort($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.username');
        if ($parameter) {
            $config->setUsername($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.password');
        if ($parameter) {
            $config->setPassword($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.encryption');
        if ($parameter) {
            $config->setEncryption($parameter->getValue());
            $config->setOverride(true);
        }

        $parameter = $parameterManager->findParameter('mail.auth_mode');
        if ($parameter) {
            $config->setAuthMode($parameter->getValue());
            $config->setOverride(true);
        }

        return $config;
    }

    public function saveMailConfig(MailConfig $config)
    {
        $parameterManager = $this->getParameterManager();

        if (!$config->isOverride()) {
            $parameter = $parameterManager->findParameter('mail.transport');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.host');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.port');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.username');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.password');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.encryption');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }

            $parameter = $parameterManager->findParameter('mail.auth_mode');
            if ($parameter) {
                $parameterManager->deleteParameter($parameter, false);
            }
        } else {
            $parameter = $parameterManager->findParameter('mail.transport');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.transport');
            }
            $parameter->setValue($config->getTransport());
            $parameterManager->saveParameter($parameter, true);

            $parameter = $parameterManager->findParameter('mail.host');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.host');
            }
            $parameter->setValue($config->getHost());
            $parameterManager->saveParameter($parameter, false);

            $parameter = $parameterManager->findParameter('mail.port');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.port');
            }
            $parameter->setValue($config->getPort());
            $parameterManager->saveParameter($parameter, false);

            $parameter = $parameterManager->findParameter('mail.username');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.username');
            }
            $parameter->setValue($config->getUsername());
            $parameterManager->saveParameter($parameter, false);

            $parameter = $parameterManager->findParameter('mail.password');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.password');
            }
            $parameter->setValue($config->getPassword());
            $parameterManager->saveParameter($parameter, false);

            $parameter = $parameterManager->findParameter('mail.encryption');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.encryption');
            }
            $parameter->setValue($config->getEncryption());
            $parameterManager->saveParameter($parameter, false);

            $parameter = $parameterManager->findParameter('mail.auth_mode');
            if (!$parameter) {
                $parameter = new Parameter();
                $parameter->setName('mail.auth_mode');
            }
            $parameter->setValue($config->getAuthMode());
            $parameterManager->saveParameter($parameter, false);
        }

        $parameterManager->getObjectManager()->flush();
    }

    /**
     * Parameter Manager
     *
     * @return ParameterManager
     */
    public function getParameterManager()
    {
        return $this->parameterManager;
    }

    /**
     * Set the parameter Manager
     *
     * @param ParameterManager $parameterManager
     */
    public function setParameterManager(ParameterManager $parameterManager)
    {
        $this->parameterManager = $parameterManager;
        return $this;
    }
}