<?php
class Conexion {

    public static $host = 'localhost';
    public static $dbname = 'wms';
    public static $username = 'root';
    public static $password = '';

    public static function get_connection() {
        // Crear conexión
        $connection = mysqli_connect(
            self::$host,
            self::$username,
            self::$password,
            self::$dbname
        );

        // Verificar conexión
        if ($connection === false) {
            echo 'No se pudo conectar a MySQL';
            die;
        } else {
            return $connection;
        }
    }

    // Iniciar una transacción
    public static function begin_transaction($connection) {
        mysqli_begin_transaction($connection);
    }

    // Confirmar la transacción
    public static function commit_transaction($connection) {
        mysqli_commit($connection);
    }

    // Revertir la transacción
    public static function rollback_transaction($connection) {
        mysqli_rollback($connection);
    }
}

