<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php');

class Personal {
    private $personal_id;
    private $nombre;
    private $primer_apellido;
    private $segundo_apellido;
    private $edificio_id;
    private $username; // Nueva propiedad para almacenar el nombre de usuario

    public function __construct($personal_id = null, $nombre = null, $primer_apellido = null, $segundo_apellido = null, $edificio_id = null, $username = null) {
        $this->personal_id = $personal_id;
        $this->nombre = $nombre;
        $this->primer_apellido = $primer_apellido;
        $this->segundo_apellido = $segundo_apellido;
        $this->edificio_id = $edificio_id;
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }


    // Getters y Setters
    public function getPersonalId() {
        return $this->personal_id;
    }

    public function setPersonalId($personal_id) {
        $this->personal_id = $personal_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getPrimerApellido() {
        return $this->primer_apellido;
    }

    public function setPrimerApellido($primer_apellido) {
        $this->primer_apellido = $primer_apellido;
    }

    public function getSegundoApellido() {
        return $this->segundo_apellido;
    }

    public function setSegundoApellido($segundo_apellido) {
        $this->segundo_apellido = $segundo_apellido;
    }




    public function getEdificioId() {
        return $this->edificio_id;
    }

    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }

    // Método para cargar los datos específicos de un personal basado en cuenta_id

}