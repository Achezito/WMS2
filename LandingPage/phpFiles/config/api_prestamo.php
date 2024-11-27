<?php

header('Content-Type: application/json');

session_start();

require_once '../../phpFiles/Models/prestamos.php';
require_once '../../LandingPage/phpFiles/Models/inventario.php';
require_once '../../LandingPage/phpFiles/config/conexion.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Acción 'get': Obtener detalles de préstamo y materiales disponibles
    if ($action === 'get') {
        $operacion_id = intval($_GET['operacion_id'] ?? 0);
        
        if ($operacion_id <= 0) {
            echo json_encode(["error" => "ID de operación inválido."]);
            exit();
        }

        // Obtener los detalles del préstamo
        $resultadoPrestamo = Prestamo::obtenerDetalles($operacion_id);

        if (!$resultadoPrestamo) {
            echo json_encode(["error" => "Préstamo no encontrado."]);
            exit();
        }

        // Obtener materiales disponibles para el edificio del usuario
        if (!isset($_SESSION['edificio_id'])) {
            echo json_encode(["error" => "No se ha asignado un edificio al usuario actual."]);
            exit();
        }

        $edificio_id = $_SESSION['edificio_id'];
        $materiales = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);

        if (!$materiales) {
            echo json_encode(["error" => "No se encontraron materiales disponibles para el edificio."]);
            exit();
        }

        // Responder con los detalles del préstamo y los materiales disponibles
        echo json_encode([
            'prestamo' => $resultadoPrestamo,
            'materiales' => $materiales
        ]);
    }

    // Acción 'update': Actualizar detalles del préstamo
    elseif ($action === 'update') {
        ob_start();
        
        // Decodificamos los datos del cuerpo de la solicitud
        $datos = json_decode(file_get_contents("php://input"), true);

        // Verifica que los datos necesarios estén presentes
        if (isset($datos['prestamo_id'], $datos['notas'], $datos['material_ids']) && is_array($datos['material_ids']) && count($datos['material_ids']) > 0) {
            $resultado = Prestamo::actualizarPrestamo($datos);

            // Comprobamos el resultado de la actualización
            if ($resultado['success']) {
                echo json_encode(["success" => true, "message" => "Préstamo actualizado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al actualizar el préstamo: " . $resultado['error']]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos o incorrectos para actualizar el préstamo."]);
        }
    }

    // Acción 'delete': Eliminar préstamo
    elseif ($action === 'delete') {
        $operacion_id = intval($_GET['operacion_id'] ?? 0);

        // Validar que el operacion_id sea válido
        if ($operacion_id > 0) {
            $resultado = Prestamo::eliminarPrestamo($operacion_id);

            // Responder según el resultado de la eliminación
            if ($resultado['success']) {
                echo json_encode(["success" => true, "message" => "Préstamo eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al eliminar el préstamo: " . $resultado['error']]);
            }
        } else {
            echo json_encode(["error" => "ID de préstamo no válido."]);
        }
    }
} else {
    echo json_encode(["error" => "Acción no válida."]);
}
