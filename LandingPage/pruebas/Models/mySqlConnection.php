<?php
class Conexion {

    public static $host = 'localhost';
    public static $dbname = 'pruebaproyecto';
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
}
