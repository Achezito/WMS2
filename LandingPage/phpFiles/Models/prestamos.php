<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php');
  class Prestamo {
    private $prestamo_id;
    private $fecha_salida;
    private $fecha_devolucion;
    private $notas;
    private $personal_id;
    private $usuario_id;

    // Constructor
    public function __construct($prestamo_id, $fecha_salida, $fecha_devolucion, $notas, $personal_id, $usuario_id) {
        $this->prestamo_id = $prestamo_id;
        $this->fecha_salida = $fecha_salida;
        $this->fecha_devolucion = $fecha_devolucion;
        $this->notas = $notas;
        $this->personal_id = $personal_id;
        $this->usuario_id = $usuario_id;
    }


    // Getters y Setters

    // prestamo_id
    public function getPrestamoId() {
        return $this->prestamo_id;
    }

    public function setPrestamoId($prestamo_id) {
        $this->prestamo_id = $prestamo_id;
    }

    // fecha_salida
    public function getFechaSalida() {
        return $this->fecha_salida;
    }

    public function setFechaSalida($fecha_salida) {
        $this->fecha_salida = $fecha_salida;
    }

    // fecha_devolucion
    public function getFechaDevolucion() {
        return $this->fecha_devolucion;
    }

    public function setFechaDevolucion($fecha_devolucion) {
        $this->fecha_devolucion = $fecha_devolucion;
    }

    // notas
    public function getNotas() {
        return $this->notas;
    }

    public function setNotas($notas) {
        $this->notas = $notas;
    }

    // personal_id
    public function getPersonalId() {
        return $this->personal_id;
    }

    public function setPersonalId($personal_id) {
        $this->personal_id = $personal_id;
    }

    // usuario_id
    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function obtenerFechaSalidaConNow() {
        // Obtener la conexión
        $con = Conexion::get_connection();
    
        // Consulta SQL para obtener la fecha de salida
        $sql = "SELECT fecha_salida FROM prestamo WHERE fecha_salida <= NOW() AND prestamo_id = ?";
        
        // Preparar la consulta
        $stmt = $con->prepare($sql);
        
        // Vincular el parámetro (prestamo_id)
        $stmt->bind_param("i", $this->prestamo_id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
    
        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fecha_salida'];  // Retorna la fecha de salida
        } else {
            return null;  // No se encontró el préstamo
        }
    }
    
}




?>  