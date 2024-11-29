<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/personal.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';
require_once BASE_PATH . '/phpFiles/Models/administrador.php';


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
           u.estado as Estado,
           u.nombre as NombreUsuario,
           u.correo as CorreoUsuario,
           p.edificio_id as EdificioPersonal,
           u.edificio_id as EdificioUsuario,
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
            $estado,
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
                    return new Personal($personal_id, $fullName, $primerApellido, null, $telefono, $correoPersonal, $edificio_idPersonal, $username);
                } else if ($type === 'usuario') {
                    return new Usuario($usuario_id, $nombreUsuario, null, $estado, $correoUsuario, $edificio_idUsuario, $username);
                } else if ($type === 'administrador') { // Nuevo caso para administrador
                    return new Administrador($id, $username);
                }
            } else {
                return "Nombre o contraseña incorrecta";
            }
        } else {
            return "El usuario no existe";
        }
    }
   
    
        // Método para obtener todas las cuentas
      
    // Consulta SQL para obtener todas las cuentas asociadas con un edificio específico
    private static $selectAll = "
    SELECT c.cuenta_id, c.nombre_usuario, c.tipo_cuenta, c.usuario_id, c.personal_id
    FROM cuentas c
    LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
    LEFT JOIN personales p ON c.personal_id = p.personal_id
    WHERE u.edificio_id = ? OR p.edificio_id = ?;
    ";

    // Método para obtener cuentas asociadas a un edificio específico
    public static function obtenerCuentasPorEdificio($edificio_id)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión: " . $connection->connect_error;
        }

        // Preparar la consulta
        $command = $connection->prepare(self::$selectAll);
        $command->bind_param('ii', $edificio_id, $edificio_id);  // Se usa 'ii' para pasar dos enteros
        $command->execute();

        // Vincular los resultados a las variables
        $command->bind_result(
            $cuenta_id,
            $nombre_usuario,
            $tipo_cuenta,
            $usuario_id,
            $personal_id
        );

        // Crear un array para almacenar las cuentas
        $cuentas = [];

        // Recuperar los resultados
        while ($command->fetch()) {
            // Agregar la cuenta al array
            $cuentas[] = [
                'cuenta_id' => $cuenta_id,
                'nombre_usuario' => $nombre_usuario,
                'tipo_cuenta' => $tipo_cuenta,
                'usuario_id' => $usuario_id,
                'personal_id' => $personal_id
            ];
        }

        // Cerrar la consulta y la conexión
        $command->close();
        $connection->close();

        // Devolver las cuentas obtenidas
        return $cuentas;
    }

    }
    

   
