<?php 
class Edificios {
    private $edificio_id;
    private $nombre;


    private static $showInformation = 
    "SELECT 
            edificio_id,
            nombre

    FROM edificios

    WHERE edificio_id = ?
    ";
    // Constructor
    public function __construct($edificio_id, $nombre) {
        $this->edificio_id = $edificio_id;
        $this->nombre = $nombre;
    }

    // Getter para edificio_id
    public function getEdificioId() {
        return $this->edificio_id;
    }

    // Setter para edificio_id
    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }

    // Getter para nombre
    public function getNombre() {
        return $this->nombre;
    }

    // Setter para nombre
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public static function mostrarInformacion($edificio_id) {
        // Obtener la conexión a la base de datos
        $connection = Conexion::get_connection();
        
        // Preparar la consulta
        $stmt = $connection->prepare(self::$showInformation);
        $stmt->bind_param("i", $edificio_id);  // 'i' para entero
        $stmt->execute();
        
        // Obtener los resultados
        $result = $stmt->get_result();
        
        // Verificar si se encontró un resultado
        if ($row = $result->fetch_assoc()) {
            // Crear y devolver un objeto Edificios
            $edificio = new Edificios(
                $row['edificio_id'],
                $row['nombre']
            );
            $stmt->close();
            $connection->close();
            return $edificio;
        } else {
            // Si no se encuentra el edificio, devolver null
            $stmt->close();
            $connection->close();
            return null;
        }
    }
    
}
?>




