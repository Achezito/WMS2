<?php

header('Content-Type: application/json');

session_start();

require_once 'C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/prestamos.php';
require_once 'C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/inventario.php';
require_once 'C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'get') {
        // Obtener detalles del préstamo
        $operacion_id = intval($_GET['operacion_id'] ?? 0);
        $resultadoPrestamo = Prestamo::obtenerDetalles($operacion_id);

        // Obtener materiales disponibles para el edificio del usuario
        if (isset($_SESSION['edificio_id'])) {
            $edificio_id = $_SESSION['edificio_id'];
            $materiales = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
        } else {
            echo json_encode(["error" => "No se ha asignado un edificio al usuario actual."]);
            exit();
        }

        if ($resultadoPrestamo) {
            echo json_encode([
                'prestamo' => $resultadoPrestamo,
                'materiales' => $materiales
            ]);
        } else {
            echo json_encode(["error" => "Préstamo no encontrado"]);
        }
    } elseif ($action === 'update') {
        // Procesamos la actualización del préstamo
        ob_start();

        // Decodificamos los datos del cuerpo de la solicitud
        // Asegúrate de que el contenido de la solicitud sea JSON
        $datos = json_decode(file_get_contents("php://input"), true);

        // Mostrar los datos que se reciben para depurar
        error_log("Datos recibidos en 'update': " . print_r($datos, true));

        // Verifica si 'prestamo_id' está en el arreglo recibido
        if (isset($datos['prestamo_id'], $datos['notas'], $datos['material_id'])) {
            $resultado = Prestamo::actualizarPrestamo($datos);
        
            // Registrar el resultado completo para depurar
            error_log("Resultado de la actualización: " . print_r($resultado, true));
        
            // Comprobamos el resultado de la actualización
            if ($resultado['success']) {
                echo json_encode(["success" => true, "message" => "Préstamo actualizado correctamente."]);
            } else {
                error_log("Error al actualizar el préstamo: " . $resultado['error']);
                echo json_encode(["error" => "Error al actualizar el préstamo: " . $resultado['error']]);
            }
        } else {
            error_log("Datos incompletos para actualizar el préstamo");
            echo json_encode(["error" => "Datos incompletos para actualizar el préstamo."]);
        }
    } elseif ($action === 'delete') {
        // Eliminar préstamo
        $operacion_id = intval($_GET['operacion_id'] ?? 0);

        if ($operacion_id > 0) {
            $resultado = Prestamo::eliminarPrestamo($operacion_id);

            if ($resultado['success']) {
                echo json_encode(["success" => true, "message" => "Préstamo eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al eliminar el préstamo: " . $resultado['error']]);
            }
        } else {
            echo json_encode(["error" => "ID de préstamo no válido."]);
        }
    }
}
