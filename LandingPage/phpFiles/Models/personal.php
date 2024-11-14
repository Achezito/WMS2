<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php');

class Personal {
    private $personal_id;
    private $nombre;
    private $primer_apellido;
    private $segundo_apellido;
    private $correo;
    private $worker_user;
    private $edificio_id;

    private static $login = "SELECT personal_id, worker_user, correo, worker_password FROM personales WHERE worker_user = ?";
   
    private static $checkUser = "SELECT worker_user FROM personales WHERE worker_user = ? ;";

    // Constructor para inicializar los atributos
    public function __construct($personal_id = null, $nombre = null, $primer_apellido = null, $segundo_apellido = null, $correo = null, $worker_user = null, $edificio_id = null)
    {
        $this->personal_id = $personal_id ?? '';
        $this->nombre = $nombre ?? '';
        $this->primer_apellido = $primer_apellido ?? '';
        $this->segundo_apellido = $segundo_apellido ?? '';
        $this->correo = $correo ?? '';
        $this->worker_user = $worker_user ?? '';
        $this->edificio_id = $edificio_id ?? '';
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

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getWorkerUser() {
        return $this->worker_user;
    }

    public function setWorkerUser($worker_user) {
        $this->worker_user = $worker_user;
    }

    public function getEdificioId() {
        return $this->edificio_id;
    }

    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }


    public static function checkEdificio(){
        
    }

    public static function login($worker_user, $password) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión: " . $connection->connect_error;
        }
    
        // Verificar si el usuario existe
        $checkCommand = $connection->prepare(self::$checkUser);
        $checkCommand->bind_param('s', $worker_user);
        $checkCommand->execute();
        $checkCommand->store_result();
    
        if ($checkCommand->num_rows === 0) {
            $checkCommand->close();
            return "El usuario no existe";  
        }
        $checkCommand->close();
    
        // Preparar y ejecutar la consulta para obtener el hash de la contraseña
        $command = $connection->prepare(self::$login);
        if ($command === false) {
            return "Error al preparar la consulta";
        }
    
        $command->bind_param('s', $worker_user);
        $command->execute();
        $command->bind_result($personal_id, $worker_user, $correo, $hashed_password);
    
        if ($command->fetch()) {
            // Comparar la contraseña ingresada con el hash almacenado en la base de datos
            if (sha1($password) === $hashed_password) {
                // Si la contraseña es correcta, retornar un nuevo objeto Personal sin el hash de la contraseña

                return new Personal($personal_id, null, null, null, $correo, $worker_user, null);
            } else {
                // Si la contraseña no coincide
                return "Nombre o contraseña incorrecta";
            }
        }
    
        return null;
    }
    
}
?>
