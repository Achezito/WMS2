<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php');

class Usuario {
    private $usuario_id;
    private $nombre;
    private $descripcion;
    private $estado;
   

    public function __construct($usuario_id = null, $nombre = null, $descripcion = null, $estado = null) {
        $this->usuario_id = $usuario_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado ?? 'activo';
    }

    // Getters y Setters
    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getEstado() {
        return $this->estado;
    }

    // Método de login
 
}
?>
