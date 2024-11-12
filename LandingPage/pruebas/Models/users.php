<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/mySqlConnection.php');
class User
{

    //statements 
    private static $login = "SELECT id_users, user_name ,user_password FROM users where user_name = ?;";
    private static $signUp = "INSERT INTO users (user_name, user_password) VALUES (?,?);";
    private static $checkUser = "SELECT id_users FROM users WHERE user_name = ?;";

    private $id_user;
    private $user_name;
    private $user_password;

    // Getter para id_user
    public function getIdUser()
    {
        return $this->id_user;
    }

    // Setter para id_user
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    // Getter para user_name
    public function getUserName()
    {
        return $this->user_name;
    }

    // Setter para user_name
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    // Setter para user_password
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    //CREACION DEL CONSTRUCTOR
    public function __construct()
    {
        //zero arguments
        if (func_num_args() == 0) {
            $this->id_user = '';
            $this->user_name = '';
         
        }
        //two arguments received, create object with values from arguments
        if (func_num_args() == 2) {
            $args = func_get_args(); //read arguments into array
            $this->id_user = $args[0];
            $this->user_name = $args[1];
        
        }
    }


   

    public static function process_register($user_name, $user_password)
    {


        $connection = Conexion::get_connection();

        // Verificar si el usuario ya existe
        $checkCommand = $connection->prepare(self::$checkUser);
        $checkCommand->bind_param('s', $user_name);
        $checkCommand->execute();
        $checkCommand->store_result();

        // Si el usuario ya existe, no insertamos
        if ($checkCommand->num_rows > 0) {
            $checkCommand->close();
           return "El nombre de usuario ya está registrado.";  
        
        }



        $hashed_password = sha1($user_password);

        $command = $connection->prepare(self::$signUp);
        $command->bind_param('ss', $user_name, $hashed_password);
        $command->execute();
        if ($command->affected_rows > 0) {
            return "Usuario registrado con éxito.";

        } else {
            return false;
            
        }
        $command->close();
    }


   public static function login($user_name, $password){
    // Obtener la conexión a la base de datos
    $connection = Conexion::get_connection();




    // Verificar si la conexión fue exitosa
    if ($connection->connect_error) {
        // Manejar el error de conexión
        return null;
    }

    $checkCommand = $connection->prepare(self::$checkUser);
        $checkCommand->bind_param('s', $user_name);
        $checkCommand->execute();
        $checkCommand->store_result();

        if ($checkCommand->num_rows === 0) {
            $checkCommand->close();
           return "El usuario no existe";  
        }
        $checkCommand->close();

    // Preparar la consulta para obtener el usuario y su hash de contraseña
    $command = $connection->prepare(self::$login);
    if ($command === false) {
        // Manejar el error si la consulta no se prepara correctamente
        return null;
    }

    $command->bind_param('s', $user_name);
    $command->execute();

    // Obtener el resultado
    $command->bind_result($id_user, $user_name, $hashed_password);

    // Verificar si se encontró al usuario
    if ($command->fetch()) {
        // Depuración: Mostrar usuario encontrado y el hash de la contraseña (elimina esta línea en producción)
        echo "Usuario encontrado: $user_name - Hash de contraseña: $hashed_password\n";

        // Comparar la contraseña ingresada con el hash almacenado en la base de datos
        if (sha1($password) === $hashed_password) {
            // Si la contraseña es correcta, retornar un nuevo objeto User
            return new User($id_user, $user_name);
        } else {
            // Si la contraseña no coincide
            return "Nombre o contraseña incorrecta";
        }
    }

    // Si no se encuentra el usuario
    return null;
}
}
