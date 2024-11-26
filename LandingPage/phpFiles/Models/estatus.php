<?php
class Estatus {
    private $estatus_id;
    private $estauts;

 

    // Constructor para inicializar las propiedades
    public function __construct($estatus_id = null, $estauts = null) {
        $this->estatus_id = $estatus_id;
        $this->estauts = $estauts;
    }

    // Getter para estatus_id
    public function getEstatusId() {
        return $this->estatus_id;
    }

    // Setter para estatus_id
    public function setEstatusId($estatus_id) {
        $this->estatus_id = $estatus_id;
    }

    // Getter para estauts
    public function getEstatus() {
        return $this->estauts;
    }

    // Setter para estauts
    public function setEstatus($estauts) {
        $this->estauts = $estauts;
    }
}
?>
