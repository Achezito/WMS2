<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/personal.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';

class Cuenta
{
    private static $loginQuery = "
        SELECT c.tipo_cuenta AS type, 
               c.cuenta_id AS id, 
               c.nombre_usuario AS username, 
               c.contraseña AS password, 
               p.personal_id AS personal_id, 
               p.nombre as Nombre,
               p.primer_apellido as PrimerApellido,
               p.telefono as Telefono,
               p.correo as Correo,
               u.nombre as NombreUsuario,
               u.correo as CorreoUsuario,
               p.edificio_id as Edificio,
               u.edificio_id as Edificio,
               u.usuario_id AS usuario_id
        FROM cuentas c
        LEFT JOIN personales p ON c.personal_id = p.personal_id
        LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
        WHERE c.nombre_usuario = ?
    ";

    // Ejemplo del flujo de login en Cuenta.php:
    public static function login($username, $password)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión: " . $connection->connect_error;
        }

        $command = $connection->prepare(self::$loginQuery);
        $command->bind_param('s', $username);
        $command->execute();
        $command->bind_result(
            $type,
            $id,
            $username,
            $hashed_password,
            $personal_id,
            $nombre,
            $primerApellido,
            $telefono,
            $correoPersonal,
            $nombreUsuario,
            $correoUsuario,
            $edificio_idPersonal,
            $edificio_idUsuario,
            $usuario_id
        );

        if ($command->fetch()) {
            if (sha1($password) === $hashed_password) {
                if ($type === 'personal') {
                    $fullName = Personal::setFullname($nombre, $primerApellido);
                    return new Personal($personal_id, $fullName, $primerApellido, null,$telefono, $correoPersonal ,$edificio_idPersonal, $username);
                } else if ($type === 'usuario') {
                    return new Usuario($usuario_id, $nombreUsuario, null,null,$correoUsuario,$edificio_idUsuario, $username);
                }
            } else {
                return "Nombre o contraseña incorrecta";
            }
        } else {
            return "El usuario no existe";
        }
    }


   
}