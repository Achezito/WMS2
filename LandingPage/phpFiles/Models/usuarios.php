<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';

class Usuario {
    private $usuario_id;
    private $nombre;
    private $fecha_creacion;
    private $estado;
    private $correo;
    private $edificio_id;
    private $username;

    private static $selectAll = 
    "SELECT 
    usuario_id as Numero_de_Usuario,
    Nombre,
    fecha_creacion,
    estado,
    correo,
    edificio_id
    FROM usuarios
    WHERE edificio_id = ?";

    public function __construct($usuario_id = null, $nombre = null, $fecha_creacion = null, 
    $estado = null,$correo = null ,$edificio_id = null, $username = null) {
        $this->usuario_id = $usuario_id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->fecha_creacion = $fecha_creacion;
        $this->estado = $estado ?? 'activo';
        $this->edificio_id = $edificio_id;
        $this->username = $username;
    }
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }


    // Getters
    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

 

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getEdificioId() {
        return $this->edificio_id;
    }

    // Setters
    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

 

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getCorreo() {
        return $this->correo;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }


    public static function InsertNewUser(){

    }

    // Método estático para obtener los usuarios de un edificio
    public static function obtenerUsuarios($edificio_id)
    {
        // Obtener la conexión a la base de datos
        $connection = Conexion::get_connection();
        
        // Consulta SQL para obtener los usuarios
        $query = "SELECT usuario_id, nombre, fecha_creacion, estado, telefono,correo, edificio_id FROM usuarios WHERE edificio_id = ?";
        
        try {
            // Preparar la consulta
            $stmt = $connection->prepare($query);
            
            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . $connection->error);
            }
    
            // Vincular el parámetro para el id del edificio
            $stmt->bind_param("i", $edificio_id);  // 'i' para entero
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener los resultados
            $result = $stmt->get_result();
    
            // Inicializar un array para almacenar los usuarios
            $usuarios = [];
            
            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                // Recorrer los resultados y crear los objetos Usuario
                while ($row = $result->fetch_assoc()) {
                    $usuario = new Usuario(
                        $row['usuario_id'],
                        $row['nombre'],
                        $row['fecha_creacion'],
                        $row['estado'],
                        $row['telefono'],
                        $row['correo'],
                        $row['edificio_id']
                    );
                    $usuarios[] = $usuario; // Agregar usuario al array
                }
            } else {
                // Si no hay usuarios, imprimir mensaje de depuración
                echo "No se encontraron usuarios para este edificio.";
            }
    
            // Cerrar la consulta
            $stmt->close();
            
        } catch (Exception $e) {
            // Manejo de excepciones: mostrar un mensaje de error o loguearlo
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return []; // Devolver un array vacío en caso de error
        } finally {
            // Cerrar la conexión
            $connection->close();
        }
    
        // Devolver el array de objetos Usuario
        return $usuarios;
    }
    
    
    public static function get_one($usuario_id)
{
    // Obtener la conexión a la base de datos
    $connection = Conexion::get_connection();

    // Consulta SQL para obtener un solo usuario por su ID
    $selectOne = "SELECT 
                    usuario_id as Numero_de_Usuario,
                    Nombre,
                    fecha_creacion,
                    estado,
                    correo,
                    edificio_id
                  FROM usuarios
                  WHERE usuario_id = ?";  // Utilizamos el usuario_id para filtrar

    // Preparar la consulta
    $stmt = $connection->prepare($selectOne);
    
    // Vincular el parámetro para el id del usuario
    $stmt->bind_param("i", $usuario_id);  // 'i' para entero
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado
    $result = $stmt->get_result();
    
    // Verificar si se encontró un usuario
    if ($result->num_rows > 0) {
        // Obtener el primer (y único) resultado
        $row = $result->fetch_assoc();
        
        // Crear y devolver el objeto Usuario
        $usuario = new Usuario(
            $row['Numero_de_Usuario'],
            $row['Nombre'],
            $row['fecha_creacion'],
            $row['estado'],
            $row['correo'],
            $row['edificio_id']
        );
        
        // Cerrar la consulta y la conexión
        $stmt->close();
        $connection->close();
        
        return $usuario;
    } else {
        // No se encontró el usuario con ese id
        $stmt->close();
        $connection->close();
        return null;
    }
}

    

    // Este es un ejemplo de cómo podría ser la clase Usuario

    
    
}
?>
