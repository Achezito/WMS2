<?php
class Conexion
{

    public static $host = 'localhost';
    public static $dbname = 'wms';
    public static $username = 'root';
    public static $password = '';

    public static function get_connection()
    {
        // Crear conexión
        $connection = mysqli_connect(
            self::$host,
            self::$username,
            self::$password,
            self::$dbname
        );

        // Verificar conexión
        if ($connection === false) {
            // Registrar el error
            error_log('Error al conectar a MySQL: ' . mysqli_connect_error());
            return null; // Devuelve null para manejarlo mejor en el flujo de trabajo
        }

        return $connection;
    }


    // Iniciar una transacción
    public static function begin_transaction($connection)
    {
        if (!$connection || !($connection instanceof mysqli)) {
            error_log("Error: Conexión inválida al intentar iniciar una transacción.");
            return false;
        }
        mysqli_begin_transaction($connection);
        return true;
    }

    // Confirmar la transacción
    public static function commit_transaction($connection)
    {
        if (!$connection || !($connection instanceof mysqli)) {
            error_log("Error: Conexión inválida al intentar confirmar una transacción.");
            return false;
        }
        mysqli_commit($connection);
        return true;
    }

    // Revertir la transacción
    public static function rollback_transaction($connection)
    {
        if (!$connection || !($connection instanceof mysqli)) {
            error_log("Error: Conexión inválida al intentar revertir una transacción.");
            return false;
        }
        mysqli_rollback($connection);
        return true;
    }
}
