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

    public function setUsername($username) {
        $this->username = $username;
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


    public static function setFullname($nombre, $primerApellido) {
        $fullname = $nombre . ' ' . $primerApellido;
        return $fullname;
    }
    

    public function getFullname() {
        return $this->nombre . ' ' . $this->primer_apellido;
    }
    


    public function getEdificioDetails() {
        $connection = Conexion::get_connection();
        $query = "SELECT nombre FROM edificios WHERE edificio_id = ?";  // Consulta para obtener nombre y dirección del edificio

        // Preparar la consulta y ejecutar
        $command = $connection->prepare($query);
        $command->bind_param('i', $this->edificio_id);  // Usamos el edificio_id del objeto
        $command->execute();
        $command->bind_result($nombre_edificio, $direccion_edificio);  // Resultados de la consulta

        if ($command->fetch()) {
            return array("nombre" => $nombre_edificio);
        } else {
            return "Edificio no encontrado";  // Si no se encuentra el edificio
        }

        $connection->close();
    }
}

