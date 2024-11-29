<?php 
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';
class inventario {
    private $material_id;
    private $serie;
    private $modelo;
    private $edificio_id;
    private $estatus_id;
    private $tipo_material_id;

    

    // Getters y Setters
    public function getMaterialId() {
        return $this->material_id;
    }

    public function setMaterialId($material_id) {
        $this->material_id = $material_id;
    }

    public function getSerie() {
        return $this->serie;
    }

    public function setSerie($serie) {
        $this->serie = $serie;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function setModelo($modelo) {
        $this->modelo = $modelo;
    }

    public function getEdificioId() {
        return $this->edificio_id;
    }

    public function setEdificioId($edificio_id) {
        $this->edificio_id = $edificio_id;
    }

    public function getEstatusId() {
        return $this->estatus_id;
    }

    public function setEstatusId($estatus_id) {
        $this->estatus_id = $estatus_id;
    }

    public function getTipoMaterialId() {
        return $this->tipo_material_id;
    }

    public function setTipoMaterialId($tipo_material_id) {
        $this->tipo_material_id = $tipo_material_id;
    }

    public static function checkMaterials(){

    }

 
    public static function obtenerMaterialesPorEdificio($edificio_id) {
        $connection = Conexion::get_connection();
        $sql = "
            SELECT 
                i.material_id, 
                i.serie, 
                i.modelo, 
                tm.nombre AS tipo_material, 
                tm.tipo_material_id, 
                e.nombre, 
                es.estatus, 
                e.nombre as edificio
            FROM 
                wms.inventario i
            JOIN 
                wms.tipo_material tm ON i.tipo_material_id = tm.tipo_material_id
            JOIN 
                wms.edificios e ON i.edificio_id = e.edificio_id
            JOIN 
                estatus es ON i.estatus_id = es.estatus_id
            WHERE 
                i.edificio_id = ?
        ";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $edificio_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $materiales = [];
        while ($row = $result->fetch_assoc()) {
            $materiales[] = $row;
        }

        $stmt->close();
        $connection->close();

        return $materiales;
    }

    public static function obtenerMaterialesPorEdificioYEstatus($edificio_id)
    {
        $connection = Conexion::get_connection();
        $sql = "
            SELECT i.modelo, tm.nombre AS tipo_material, i.material_id
            FROM wms.inventario i
            JOIN wms.tipo_material tm ON i.tipo_material_id = tm.tipo_material_id
            JOIN wms.edificios e ON i.edificio_id = e.edificio_id
            JOIN estatus es on i.estatus_id = es.estatus_id
            WHERE i.edificio_id = ? AND es.estatus = 'Disponible';
        ";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $edificio_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $materiales = [];
        while ($row = $result->fetch_assoc()) {
            $materiales[] = $row;
        }

        $stmt->close();
        $connection->close();

        return $materiales;
    }

    public static function actualizarMaterial($material_id, $serie, $modelo, $tipo_material_id) {
        $connection = Conexion::get_connection();
        $sql = "UPDATE wms.inventario SET serie = ?, modelo = ?, tipo_material_id = ? WHERE material_id = ?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssii", $serie, $modelo, $tipo_material_id, $material_id);
        $result = $stmt->execute();

        $stmt->close();
        $connection->close();

        return $result;
    }

    public static function obtenerTiposMateriales() {
        $connection = Conexion::get_connection();
        $sql = "SELECT tipo_material_id, nombre FROM wms.tipo_material";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $tipos_materiales = [];
        while ($row = $result->fetch_assoc()) {
            $tipos_materiales[] = $row;
        }

        $stmt->close();
        $connection->close();

        return $tipos_materiales;
    }
}
?>
