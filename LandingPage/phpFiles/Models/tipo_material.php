<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';
class TipoMaterial {
    private $tipo_material_id;
    private $nombre;
    private $categoria;
    private $descripcion;

    // Constructor
    public function __construct($tipo_material_id = null, $nombre = null, $categoria = null, $descripcion = null) {
        $this->tipo_material_id = $tipo_material_id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
    }

    // Getters
    public function getTipoMaterialId() {
        return $this->tipo_material_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    // Setters
    public function setTipoMaterialId($tipo_material_id) {
        $this->tipo_material_id = $tipo_material_id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public static function mostrarTodosLosTiposMaterial() { 
        // Obtener la conexión a la base de datos
        $connection = Conexion::get_connection();
    
        // Consulta SQL para obtener todos los edificios
        $sql = "SELECT tipo_material_id as tpi, nombre, categoria FROM tipo_material";
        $stmt = $connection->prepare($sql);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener todas las filas como un array asociativo
        $result = $stmt->get_result();
        $edificios = $result->fetch_all(MYSQLI_ASSOC);
    
        // Cerrar la conexión y devolver el array
        $stmt->close();
        $connection->close();
    
        return $edificios;
    }
}
?>
