<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/config/conexion.php');
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['id'];
    $fecha_devolucion = null;
    $estado = 'pendiente'; // Tipo de cuenta predeterminado
    $notas = $_POST['comentarios'];
    $personalID = null;
    $inventarioID = $_POST['material'];

    try {
        // Obtener la conexión a la base de datos
        $connection = Conexion::get_connection();

        // Iniciar la transacción
        $connection->begin_transaction();

        // Insertar en la tabla `prestamos`
        $stmt = $connection->prepare("
            INSERT INTO prestamos (fecha_salida, fecha_devolucion, estatus, notas, personal_id, usuario_id) 
            VALUES (NOW(), ?, ?, ?, ?, ?)
        ");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para insertar en 'prestamos'.");
        }

        $stmt->bind_param("sssii", $fecha_devolucion, $estado, $notas, $personalID, $idUsuario);
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar datos en la tabla 'prestamos'.");
        }

        // Obtener el ID del préstamo insertado
        $prestamo_id = $connection->insert_id;

        // Insertar en la tabla `inventario_prestamos`
        $stmt = $connection->prepare("
            INSERT INTO inventario_prestamos (prestamo_id, material_id) 
            VALUES (?, ?)
        ");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para insertar en 'inventario_prestamos'.");
        }

        $stmt->bind_param("ii", $prestamo_id, $inventarioID);
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar datos en la tabla 'inventario_prestamos'.");
        }

        // Confirmar la transacción
        $connection->commit();

        // Enviar respuesta JSON de éxito
        echo json_encode(['success' => true, 'message' => 'Préstamo registrado correctamente.']);
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $connection->rollback();
        
        // Enviar respuesta JSON de error
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        // Cerrar el statement y la conexión
        if ($stmt) $stmt->close();
        if ($connection) $connection->close();
    }
}
?>
