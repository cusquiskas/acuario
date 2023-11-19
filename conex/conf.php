<?php

class ConfiguracionSistema
{
    #private $host = 'srv1065.hstgr.io';
    private $host = 'localhost';
    #private $user = 'u164639268_piscina';
    private $user = 'piscina';
    private $pass = 'P3c3cit0sgr4nd3s';
    #private $apli = 'u164639268_piscina';
    private $apli = 'piscina';

    private $home = '/opt/lampp/htdocs/acuario/';

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getApli()
    {
        return $this->apli;
    }

    public function getHome()
    {
        return $this->home;
    }
}
