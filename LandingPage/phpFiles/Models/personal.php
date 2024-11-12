<?php
require_once('C:/xampp/htdocs/WMS2/WMS2/LandingPage/phpFiles/config/conexion.php');

class Personal {
    private $personal_id;
    private $nombre;
    private $primer_apellido;
    private $segundo_apellido;
    private $correo;
    private $worker_user;
    private $edificio_id;

    private static $login = "SELECT personal_id, worker_user, correo, worker_password FROM personales WHERE worker_user = ? AND correo = ?";
   
    private static $checkUser = "SELECT worker_user FROM personales WHERE worker_user = ? AND correo = ?;";

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

    // Getter y Setter para personal_id
    public function getPersonalId() {
        return $this->personal_id;
    }

    public function setPersonalId($personal_id) {
        $this->personal_id = $personal_id;
    }

    // Getter y Setter para nombre
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getter y Setter para primer_apellido
    public function getPrimerApellido() {
        return $this->primer_apellido;
    }

    public function setPrimerApellido($primer_apellido) {
        $this->primer_apellido = $primer_apellido;
    }

    // Getter y Setter para segundo_apellido
    public function getSegundoApellido() {
        return $this->segundo_apellido;
    }

    public function setSegundoApellido($segundo_apellido) {
        $this->segundo_apellido = $segundo_apellido;
    }

    // Getter y Setter para correo
    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    // Getter y Setter para worker_user
    public function getWorkerUser() {
        return $this->worker_user;
    }

    public function setWorkerUser($worker_user) {
        $this->worker_user = $worker_user;
    }

    // Getter y Setter para edificio_id
    public function getEdificioId() {
        return $this->edificio_id;
    }

    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }

     public static function login($worker_user, $email, $password){
    // Obtener la conexión a la base de datos
    $connection = Conexion::get_connection();

    // Verificar si la conexión fue exitosa
    if ($connection->connect_error) {
        // Manejar el error de conexión
        return null;
    }

    $checkCommand = $connection->prepare(self::$checkUser);
        $checkCommand->bind_param('ss', $worker_user, $email);
        $checkCommand->execute();
        $checkCommand->store_result();

        if ($checkCommand->num_rows === 0) {
            $checkCommand->close();
           return "El usuario o correo no existen";  
        }
        $checkCommand->close();

    // Preparar la consulta para obtener el usuario y su hash de contraseña
    $command = $connection->prepare(self::$login);
    if ($command === false) {
        // Manejar el error si la consulta no se prepara correctamente
        return null;
    }

    $command->bind_param('ss', $worker_user,$email);
    $command->execute();

    // Obtener el resultado
    $command->bind_result($personal_id, $worker_user, $email, $hashed_password);

    // Verificar si se encontró al usuario
    if ($command->fetch()) {
        // Depuración: Mostrar usuario encontrado y el hash de la contraseña (elimina esta línea en producción)
        echo "Usuario encontrado: $worker_user - Hash de contraseña: $hashed_password\n";

        // Comparar la contraseña ingresada con el hash almacenado en la base de datos
        if (sha1($password) === $hashed_password) {
            // Si la contraseña es correcta, retornar un nuevo objeto User
            return new Personal($personal_id, null, null, null, $email, $worker_user, null);
        } else {
            // Si la contraseña no coincide
            return "Nombre o contraseña incorrecta";
        }
    }

    // Si no se encuentra el usuario
    return null;
}
    



}
?>
