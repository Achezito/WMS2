<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';

session_start();

if (!isset($_SESSION['personal_id']) && $_SESSION['user_type'] != 'administrador' ) {
    header("Location: login.php");
    exit();
}

$personal_id = $_SESSION['personal_id'];

function obtenerPrestamos($estado) {
    $conn = Conexion::get_connection();
    $query = "SELECT * FROM prestamos WHERE estatus = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $estado);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function actualizarPrestamo($prestamo_id, $estado, $personal_id, $fecha_devolucion = null) {
    $conn = Conexion::get_connection();
    if ($estado == 'aprobado') {
        $query = "UPDATE prestamos SET estatus = ?, personal_id = ? WHERE prestamo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $estado, $personal_id, $prestamo_id);
    } else if ($estado == 'finalizado') {
        $query = "UPDATE prestamos SET estatus = ?, fecha_devolucion = ? WHERE prestamo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $estado, $fecha_devolucion, $prestamo_id);
    }
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestamo_id = $_POST['prestamo_id'];
    $estado = $_POST['estado'];

    if ($estado == 'aprobado') {
        actualizarPrestamo($prestamo_id, $estado, $personal_id);
    } else if ($estado == 'finalizado') {
        $fecha_devolucion = date('Y-m-d');
        actualizarPrestamo($prestamo_id, $estado, $personal_id, $fecha_devolucion);
    }

    header("Location: personal_prestamos.php");
    exit();
}

$prestamosPendientes = obtenerPrestamos('pendiente');
$prestamosAprobados = obtenerPrestamos('aprobado');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gestión de Préstamos</h1>

    <h2>Préstamos Pendientes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Salida</th>
                <th>Notas</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamosPendientes as $prestamo): ?>
                <tr>
                    <td><?= $prestamo['prestamo_id'] ?></td>
                    <td><?= $prestamo['fecha_salida'] ?></td>
                    <td><?= $prestamo['notas'] ?></td>
                    <td>
                        <form method="POST" action="personal_prestamos.php">
                            <input type="hidden" name="prestamo_id" value="<?= $prestamo['prestamo_id'] ?>">
                            <input type="hidden" name="estado" value="aprobado">
                            <button type="submit">Aprobar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Préstamos Aprobados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Salida</th>
                <th>Notas</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamosAprobados as $prestamo): ?>
                <tr>
                    <td><?= $prestamo['prestamo_id'] ?></td>
                    <td><?= $prestamo['fecha_salida'] ?></td>
                    <td><?= $prestamo['notas'] ?></td>
                    <td>
                        <form method="POST" action="personal_prestamos.php">
                            <input type="hidden" name="prestamo_id" value="<?= $prestamo['prestamo_id'] ?>">
                            <input type="hidden" name="estado" value="finalizado">
                            <button type="submit">Finalizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
