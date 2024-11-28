<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';

$limite_inactividad = 100000;

if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];

    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();

        header("Location: ../html/login.php?sesion=expirada");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time();

if (!isset($_SESSION['user_type'])) {
    header('location: ../html/login.php');
    exit();
}

$personal_id = $_SESSION['personal_id'];
$edificio_id = $_SESSION['edificio_id'];

function obtenerPrestamos($estado, $edificio_id) {
    $conn = Conexion::get_connection();
    $query = "SELECT p.* 
              FROM prestamos p
              JOIN usuarios u ON p.usuario_id = u.usuario_id
              WHERE p.estatus = ? AND u.edificio_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $estado, $edificio_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function actualizarPrestamo($prestamo_id, $estado, $personal_id, $fecha_devolucion = null, $notas = null) {
    $conn = Conexion::get_connection();
    $query = "";
    if ($estado == 'aprobado') {
        $query = "UPDATE prestamos SET estatus = ?, personal_id = ? WHERE prestamo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $estado, $personal_id, $prestamo_id);
    } else if ($estado == 'finalizado') {
        $query = "UPDATE prestamos SET estatus = ?, fecha_devolucion = ?, personal_id = ? WHERE prestamo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssii", $estado, $fecha_devolucion, $personal_id, $prestamo_id);
    } else if ($estado == 'rechazado') {
        $query = "UPDATE prestamos SET estatus = ?, fecha_devolucion = ?, notas = ?, personal_id = ? WHERE prestamo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssii", $estado, $fecha_devolucion, $notas, $personal_id, $prestamo_id);
    } else {
        return false;
    }
    return $stmt->execute();
}


function actualizarInventario($prestamo_id, $estado) {
    $conn = Conexion::get_connection();
    $query = "SELECT material_id FROM inventario_prestamos WHERE prestamo_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $materiales = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($materiales as $material) {
        $material_id = $material['material_id'];
        if ($estado == 'aprobado') {
            // Comprobar disponibilidad del material
            $query = "SELECT estatus_id FROM inventario WHERE material_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $material_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $estatus = $result->fetch_assoc()['estatus_id'];

            if ($estatus != 1) { // Si el material no está disponible
                return false;
            }

            // Actualizar estatus del material a 'En uso'
            $query = "UPDATE inventario SET estatus_id = 2 WHERE material_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $material_id);
            $stmt->execute();
        } else if ($estado == 'finalizado') {
            // Actualizar estatus del material a 'Disponible'
            $query = "UPDATE inventario SET estatus_id = 1 WHERE material_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $material_id);
            $stmt->execute();
        }
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestamo_id = $_POST['prestamo_id'];
    $estado = $_POST['estado'];
    $notas = $_POST['notas'] ?? null;

    if ($estado == 'aprobado') {
        if (actualizarInventario($prestamo_id, $estado)) {
            actualizarPrestamo($prestamo_id, $estado, $personal_id);
        } else {
            echo "Error: Uno o más materiales no están disponibles.";
            exit();
        }
    } else if ($estado == 'finalizado') {
        $fecha_devolucion = date('Y-m-d');
        if (actualizarInventario($prestamo_id, $estado)) {
            actualizarPrestamo($prestamo_id, $estado, $personal_id, $fecha_devolucion);
        }
    } else if ($estado == 'rechazado') {
        $fecha_devolucion = date('Y-m-d');
        actualizarPrestamo($prestamo_id, $estado, $personal_id, $fecha_devolucion, $notas);
    }

    header("Location: personal_prestamos.php");
    exit();
}

$prestamosPendientes = obtenerPrestamos('pendiente', $edificio_id);
$prestamosAprobados = obtenerPrestamos('aprobado', $edificio_id);

function dibujar_historial_materiales($prestamo_id) {
    $connection = Conexion::get_connection();

    $query = "SELECT material_id, material_nombre, serie, modelo 
              FROM historial_materiales
              WHERE CONVERT(tipo_operacion USING utf8mb4) = CONVERT('Préstamo' USING utf8mb4)
              AND operacion_id = ?";
    $command = $connection->prepare($query);
    $command->bind_param('i', $prestamo_id);  // 'i' para operacion_id (int)

    $command->execute();
    $resultado = $command->get_result();

    $materiales = [];
    while ($row = $resultado->fetch_assoc()) {
        $materiales[] = $row;
    }

    $connection->close();

    if (!empty($materiales)) {
        echo "<table id='tabla_historial_materiales' class='display' style='width:100%'>
                <thead>
                    <tr>
                        <th>Material ID</th>
                        <th>Material Nombre</th>
                        <th>Serie</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($materiales as $registro) {
            echo "<tr>";
            foreach ($registro as $campo) {
                echo "<td>" . htmlspecialchars($campo) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron materiales.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['prestamo_id'])) {
    $prestamo_id = $_GET['prestamo_id'];
    ob_start();
    dibujar_historial_materiales($prestamo_id);
    $output = ob_get_clean();
    echo $output;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/hom2.css">
    <link rel="stylesheet" href="../css/personal_prestamos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/index.js"></script>
    <script src="../js/pp_modal.js"></script>
    
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <!-- Contenedor para logo y nombre -->
            <h1 class="app-title">CISTA</h1>
        </div>
        <div class="profile">
            <!-- Perfil del usuario -->
            <img class="user-avatar" src="../img/Users/User.jpg" alt="User Avatar">
            <h3 class="titleName"><?php echo $_SESSION['fullname']; ?></h3>
            <p class="titleMail"><?php echo $_SESSION['correo']; ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="../html/personal/indice/index.php"><label class="linkLabel">Home</label></a></li>
                <li class="dropdown">
                    <span class="dropdown-toggle">Formularios</span>
                    <ul class="dropdown-menu">
                        <li><a href="../formularios/personal_prestamos.php">Préstamos</a></li>
                        <li><a href="../formularios/transacciones.php">Transacciones</a></li>
                        <li><a href="../formularios/mantenimiento.php">Mantenimiento</a></li>
                    </ul>
                </li>
                <li><a href="../html/personal/users/users.php"><label class="linkLabel">Usuarios</label></a></li>
                <li><a href="../html/personal/history/history.php"><label class="linkLabel">Historiales</label></a></li>
                <li><a href="../phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <section class="content">
            <h2>Gestión de Préstamos</h2>

            <h4>Préstamos Pendientes</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha de Salida</th>
                        <th>Notas</th>
                        <th>Acción</th>
                        <th>Ver Materiales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamosPendientes as $prestamo): ?>
                        <tr>
                            <td><?= $prestamo['prestamo_id'] ?></td>
                            <td><?= $prestamo['fecha_salida'] ?></td>
                            <td><?= $prestamo['notas'] ?></td>
                            <td class="table-buttons">
                                <form method="POST" action="personal_prestamos.php">
                                    <input type="hidden" name="prestamo_id" value="<?= $prestamo['prestamo_id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button class="button3" type="submit">Aprobar</button>
                                </form>
                                <button class="button3 rechazar-prestamo-btn" data-prestamo-id="<?= $prestamo['prestamo_id'] ?>">Rechazar</button>
                                
                            </td>
                            <td>
                                    <button class="button2 ver-materiales-btn" data-prestamo-id="<?= $prestamo['prestamo_id'] ?>">Ver Materiales</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4>Préstamos Aprobados</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha de Salida</th>
                        <th>Notas</th>
                        <th>Acción</th>
                        <th>Ver Materiales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamosAprobados as $prestamo): ?>
                        <tr>
                            <td><?= $prestamo['prestamo_id'] ?></td>
                            <td><?= $prestamo['fecha_salida'] ?></td>
                            <td><?= $prestamo['notas'] ?></td>
                            <td class="table-buttons">
                                <form method="POST" action="personal_prestamos.php">
                                    <input type="hidden" name="prestamo_id" value="<?= $prestamo['prestamo_id'] ?>">
                                    <input type="hidden" name="estado" value="finalizado">
                                    <button class="button2" type="submit">Finalizar</button>
                                </form>
                            </td>
                            <td>
                            <button class="button2 ver-materiales-btn" data-prestamo-id="<?= $prestamo['prestamo_id'] ?>">Ver Materiales</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
  </div>

  <!-- Modal de Rechazo -->
  <div id="rechazoModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Rechazar Préstamo</h4>
        <form method="POST" action="personal_prestamos.php">
            <input type="hidden" name="prestamo_id" id="rechazoPrestamoId">
            <input type="hidden" name="estado" value="rechazado">
            <div class="form-group">
                <label for="rechazoNotas">Razón del Rechazo:</label>
                <textarea name="notas" id="rechazoNotas" required></textarea>
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
  </div>

  <!-- Modal de Materiales -->
  <div id="materialesModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Materiales del Préstamo</h4>
        <div id="materialesContent"></div>
    </div>
  </div>
</body>
</html>
