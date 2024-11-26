<?php
require_once('conexion.php'); // Tu conexión a la base de datos
header('Content-Type: application/json');
// Verificar si se ha recibido la solicitud AJAX con los datos correctos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario_id']) && isset($_POST['estado']) && isset($_POST['tipo_cuenta'])) {
    // Obtener los parámetros enviados por la solicitud AJAX
    $usuario_id = $_POST['usuario_id'];
    $estado = $_POST['estado'];
    $tipo_cuenta = $_POST['tipo_cuenta'];

    // Conectar a la base de datos (ajustar según tu configuración)
    $connection = Conexion::get_connection();

    // Consulta SQL para actualizar el estado del usuario
    if ($tipo_cuenta === 'usuario') {
        $query = "UPDATE usuarios SET estado = ? WHERE usuario_id = ?";
    } else {
        $query = "UPDATE personales SET tipo_cuenta = ? WHERE usuario_id = ?";
    }

    try {
        // Preparar la consulta
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $connection->error);
        }

        // Vincular los parámetros para la consulta
        $stmt->bind_param('si', $estado, $usuario_id); // 's' para string, 'i' para integer

        // Ejecutar la consulta
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Estado del usuario actualizado con éxito.";
        } else {
            echo "No se realizaron cambios.";
        }

        // Cerrar la consulta
        $stmt->close();
    } catch (Exception $e) {
        // Manejo de errores
        echo "Error al actualizar el estado: " . $e->getMessage();
    } finally {
        // Cerrar la conexión
        $connection->close();
    }
} else {
    echo "Datos no válidos.";
}
?>
