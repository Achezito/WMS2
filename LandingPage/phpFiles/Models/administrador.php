<?php
class Administrador
{
    private $cuentaId;
    private $username;
   

    public function __construct($cuentaId, $username)
    {
        $this->cuentaId = $cuentaId;
        $this->username = $username;
       
    }

    public function getCuentaId()
    {
        return $this->cuentaId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    
}
