<?php 
class inventario {
    private $material_id;
    private $serie;
    private $modelo;
    private $edificio_id;
    private $estatus_id;
    private $tipo_material_id;

    private static $checkMaterials = "SELECT 
        i.material_id,
        i.serie,
        i.modelo,
        e.nombre AS edificio,
        s.estatus AS estatus,
        t.nombre AS tipo_material,
        p.nombre AS personal_responsable -- Suponiendo que 'nombre' en 'personales' es el responsable
    FROM 
        inventario AS i
    JOIN 
        estatus AS s ON i.estatus_id = s.estatus_id
    JOIN 
        tipo_material AS t ON i.tipo_material_id = t.tipo_material_id
    JOIN 
        edificios AS e ON i.edificio_id = e.edificio_id
    JOIN 
        personales AS p ON e.edificio_id = p.edificio_id
    WHERE e.edificio_id = p.edificio_id AND s.estatus = 'disponible';";

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
}
?>
