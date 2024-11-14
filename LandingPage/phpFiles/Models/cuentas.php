<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/personal.php');
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/usuarios.php');

class Cuenta {
    private static $loginQuery = "
        SELECT c.tipo_cuenta AS type, 
               c.cuenta_id AS id, 
               c.nombre_usuario AS username, 
               c.contraseña AS password, 
               p.personal_id AS personal_id, 
               p.nombre as Nombre,
               p.primer_apellido as PrimerApellido,
               p.edificio_id as Edificio,
               u.usuario_id AS usuario_id
        FROM cuentas c
        LEFT JOIN personales p ON c.personal_id = p.personal_id
        LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
        WHERE c.nombre_usuario = ?
    ";

    public static function login($username, $password) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión: " . $connection->connect_error;
        }

        $command = $connection->prepare(self::$loginQuery);
        $command->bind_param('s', $username);
        $command->execute();
        $command->bind_result($type, $id, $username, $hashed_password, 
        $personal_id, $nombre, $primerApellido, $edificio_id ,$usuario_id);

        if ($command->fetch()) {
            if (sha1($password) === $hashed_password) {
                if ($type === 'personal') {
                    // Crear el nombre completo usando el método setFullname
                    $fullName = Personal::setFullname($nombre, $primerApellido);
                    
                    // Retornar la instancia de Personal con el nombre completo
                    return new Personal($personal_id, $fullName, null, null, $edificio_id, $username);
                } else if ($type === 'usuario') {
                    error_log("Tipo de cuenta: usuario"); // Log temporal
                    return new Usuario($usuario_id, $username);
                }
                
            } else {
                return "Nombre o contraseña incorrecta";
            }
        } else {
            return "El usuario no existe";
        }
        
    }
}
?>
