<?php
namespace Zym\Bundle\MailBundle;

class MailConfig
{
    private $override = false;

    private $transport;
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;
    private $authMode;

    public function isOverride()
    {
        return $this->override;
    }

    public function setOverride($override)
    {
        $this->override = $override;
    }

    public function getTransport()
    {
        return $this->transport;
    }

    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEncryption()
    {
        return $this->encryption;
    }

    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
    }

    public function getAuthMode()
    {
        return $this->authMode;
    }

    public function setAuthMode($authMode)
    {
        $this->authMode = $authMode;
    }
}