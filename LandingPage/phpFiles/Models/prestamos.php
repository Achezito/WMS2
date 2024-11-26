<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';
  class Prestamo {
    private $prestamo_id;
    private $fecha_salida;
    private $fecha_devolucion;
    private $notas;
    private $personal_id;
    private $usuario_id;

    // Constructor
    public function __construct($prestamo_id, $fecha_salida, $fecha_devolucion, $notas, $personal_id, $usuario_id)
    {
        $this->prestamo_id = $prestamo_id;
        $this->fecha_salida = $fecha_salida;
        $this->fecha_devolucion = $fecha_devolucion;
        $this->notas = $notas;
        $this->personal_id = $personal_id;
        $this->usuario_id = $usuario_id;
    }


    // Getters y Setters

    // prestamo_id
    public function getPrestamoId()
    {
        return $this->prestamo_id;
    }

    public function setPrestamoId($prestamo_id)
    {
        $this->prestamo_id = $prestamo_id;
    }

    // fecha_salida
    public function getFechaSalida()
    {
        return $this->fecha_salida;
    }

    public function setFechaSalida($fecha_salida)
    {
        $this->fecha_salida = $fecha_salida;
    }

    // fecha_devolucion
    public function getFechaDevolucion()
    {
        return $this->fecha_devolucion;
    }

    public function setFechaDevolucion($fecha_devolucion)
    {
        $this->fecha_devolucion = $fecha_devolucion;
    }

    // notas
    public function getNotas()
    {
        return $this->notas;
    }

    public function setNotas($notas)
    {
        $this->notas = $notas;
    }

    // personal_id
    public function getPersonalId()
    {
        return $this->personal_id;
    }

    public function setPersonalId($personal_id)
    {
        $this->personal_id = $personal_id;
    }

    // usuario_id
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public static function obtenerHistorialDeUsuario($usuario_id, $busqueda = '')
    {
        $connection = Conexion::get_connection(); // Asegúrate de que esto retorne una instancia válida de `mysqli`
        try {
            // Base de la consulta SQL

            $sql = "SELECT * FROM prestamos_usuarios WHERE usuario_id = ?";

            // Agregar condición de búsqueda si se proporciona
            if (!empty($busqueda)) {
                $sql .= " AND (notas LIKE ? OR estatus LIKE ? OR Responsable LIKE ?)";
            }

            // Preparar la declaración
            $stmt = $connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $connection->error);
            }

            // Crear el conjunto de parámetros para la consulta
            if (!empty($busqueda)) {
                $busqueda = '%' . $busqueda . '%';
                $stmt->bind_param("isss", $usuario_id, $busqueda, $busqueda, $busqueda); // Tipos: `i` (integer) y `s` (string)
            } else {
                $stmt->bind_param("i", $usuario_id); // Solo el parámetro de usuario
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados
            $result = $stmt->get_result();

            // Convertir a un array asociativo
            $resultados = $result->fetch_all(MYSQLI_ASSOC);

            // Retornar resultados
            return $resultados;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    public static function obtenerDetalles($operacion_id)
    {
        $conn = Conexion::get_connection();
        $query = "SELECT p.prestamo_id, i.modelo AS modelo_material, tp.nombre AS tipo_material, p.notas, i.material_id 
              FROM prestamos AS p
              INNER JOIN inventario_prestamos AS ip ON p.prestamo_id = ip.prestamo_id
              INNER JOIN inventario AS i ON ip.material_id = i.material_id
              INNER JOIN tipo_material AS tp ON i.tipo_material_id = tp.tipo_material_id
              WHERE p.prestamo_id = ?;";


        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $operacion_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $materiales = [];
            $notas = null;

            while ($row = $result->fetch_assoc()) {
                $notas = $row['notas']; // Aquí aseguramos que la nota viene de la tabla prestamos
                $materiales[] = [
                    'material_id' => $row['material_id'],
                    'modelo_material' => $row['modelo_material']
                ];
            }

            return [
                'prestamo_id' => $operacion_id,
                'notas' => $notas,
                'materiales' => $materiales
            ];
        }

        return ["error" => "Préstamo no encontrado"];
    }

    public static function actualizarPrestamo($datos) 
    {
        $conn = Conexion::get_connection();
    
        if ($conn === false) {
            error_log("Error al conectar con la base de datos.");
            return ["success" => false, "error" => "Error de conexión a la base de datos"];
        }
    
        $conn->begin_transaction();
    
        try {
            // Actualizar las notas del préstamo
            $queryNotas = "UPDATE prestamos SET notas = ? WHERE prestamo_id = ?";
            $stmtNotas = $conn->prepare($queryNotas);
            if ($stmtNotas === false) {
                throw new Exception("Error al preparar la consulta para actualizar las notas.");
            }
            $stmtNotas->bind_param("si", $datos['notas'], $datos['prestamo_id']);
            $stmtNotas->execute();
    
            if ($stmtNotas->affected_rows === 0) {
                throw new Exception("No se actualizaron las notas para prestamo_id: " . $datos['prestamo_id']);
            }
    
            // Primero eliminamos los materiales antiguos del préstamo
            $queryEliminarMateriales = "DELETE FROM inventario_prestamos WHERE prestamo_id = ?";
            $stmtEliminar = $conn->prepare($queryEliminarMateriales);
            if ($stmtEliminar === false) {
                throw new Exception("Error al preparar la consulta para eliminar los materiales antiguos.");
            }
            $stmtEliminar->bind_param("i", $datos['prestamo_id']);
            $stmtEliminar->execute();
    
            if ($stmtEliminar->affected_rows === 0) {
                error_log("No se eliminaron los materiales antiguos para prestamo_id: " . $datos['prestamo_id']);
            }
    
            // Ahora agregamos los nuevos materiales
            $queryInventario = "INSERT INTO inventario_prestamos (prestamo_id, material_id) VALUES (?, ?)";
            foreach ($datos['material_ids'] as $material_id) {
                $stmtInventario = $conn->prepare($queryInventario);
                if ($stmtInventario === false) {
                    throw new Exception("Error al preparar la consulta para agregar materiales a inventario_prestamos.");
                }
                $stmtInventario->bind_param("ii", $datos['prestamo_id'], $material_id);
                $stmtInventario->execute();
    
                if ($stmtInventario->affected_rows === 0) {
                    throw new Exception("No se actualizó la relación para material_id: $material_id en prestamo_id: " . $datos['prestamo_id']);
                }
            }
    
            // Confirmar la transacción
            $conn->commit();
            return ["success" => true, "message" => "Préstamo actualizado correctamente con materiales asignados."];
        } catch (Exception $e) {
            // Revertir en caso de error
            $conn->rollback();
            error_log("Error en la transacción: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        } finally {
            if (isset($stmtNotas)) $stmtNotas->close();
            if (isset($stmtEliminar)) $stmtEliminar->close();
            if (isset($stmtInventario)) $stmtInventario->close();
            $conn->close();
        }
    }
    
    public static function eliminarPrestamo($prestamo_id)
{
    $conn = Conexion::get_connection();

    // Verificar que la conexión sea exitosa
    if ($conn === false) {
        error_log("Error al conectar con la base de datos.");
        return ["success" => false, "error" => "Error de conexión a la base de datos"];
    }

    // Iniciar la transacción
    $conn->begin_transaction();

    try {
        // Eliminar la relación en inventario_prestamos
        $queryInventario = "DELETE FROM inventario_prestamos WHERE prestamo_id = ?";
        $stmtInventario = $conn->prepare($queryInventario);
        if ($stmtInventario === false) {
            throw new Exception("Error al preparar la consulta para eliminar la relación en inventario_prestamos.");
        }
        $stmtInventario->bind_param("i", $prestamo_id);
        $stmtInventario->execute();

        if ($stmtInventario->affected_rows === 0) {
            throw new Exception("No se eliminó la relación en inventario_prestamos para prestamo_id: " . $prestamo_id);
        }

        // Eliminar el préstamo de la tabla prestamos
        $queryPrestamo = "DELETE FROM prestamos WHERE prestamo_id = ?";
        $stmtPrestamo = $conn->prepare($queryPrestamo);
        if ($stmtPrestamo === false) {
            throw new Exception("Error al preparar la consulta para eliminar el préstamo.");
        }
        $stmtPrestamo->bind_param("i", $prestamo_id);
        $stmtPrestamo->execute();

        if ($stmtPrestamo->affected_rows === 0) {
            throw new Exception("No se eliminó el préstamo con prestamo_id: " . $prestamo_id);
        }

        // Confirmar la transacción
        $conn->commit();
        return ["success" => true];
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conn->rollback();
        error_log("Error en la transacción: " . $e->getMessage());
        return ["success" => false, "error" => $e->getMessage()];
    } finally {
        // Cerrar recursos
        if (isset($stmtInventario)) $stmtInventario->close();
        if (isset($stmtPrestamo)) $stmtPrestamo->close();
        $conn->close();
    }
}

}
