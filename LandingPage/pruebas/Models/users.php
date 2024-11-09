<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/phpFiles/mySqlConnection.php');
class User {

    //statements 
    private static $login = "SELECT id_users, user_name ,user_password FROM users where user_name = ?;";

    private $id_user;
    private $user_name;
    private $user_password;

    // Getter para id_user
    public function getIdUser() {
        return $this->id_user;
    }

    // Setter para id_user
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    // Getter para user_name
    public function getUserName() {
        return $this->user_name;
    }

    // Setter para user_name
    public function setUserName($user_name) {
        $this->user_name = $user_name;
    }

    // Setter para user_password
    public function setUserPassword($user_password) {
        $this->user_password = $user_password;
    }

    //CREACION DEL CONSTRUCTOR
    public function __construct( ) {
        //zero arguments
        if (func_num_args() == 0) {
            $this->id_user = '';
            $this->user_name = '';
            $this->user_password = '';
        }
        //two arguments received, create object with values from arguments
        if (func_num_args() == 3) {
            $args = func_get_args(); //read arguments into array
            $this->id_user = $args[0];
            $this->user_name = $args[1];
            $this->user_password = $args[2];
        }
    }

    public static function login ($user_name, $password){
        //get connection
        $connection = Conexion::get_connection();
        //prepare statement
        $command = $connection->prepare(self::$login);
        //bind param
        $command -> bind_param('s', $user_name);
        //execute
        $command->execute();
        //bind_ result
        $command -> bind_result($id_user, $user_name, $hashed_password);
        //read data
        if ($command-> fetch()){

            if (sha1($password) == $hashed_password){
                return new User($id_user,$user_name, $hashed_password);
            } 
        } 
        return null;
        
    }
}

?>
