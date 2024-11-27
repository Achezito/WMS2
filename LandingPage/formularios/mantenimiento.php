<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
  header('location: /WMS2/LandingPage/html/login.php');
  exit();
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

$personal_id = $_SESSION['user_id'];
$edificio_id = $_SESSION['edificio_id'];

function obtenerMantenimientos($estado) {
  $conn = Conexion::get_connection();
  $query = "SELECT m.mantenimiento_id, m.descripcion, m.fecha_inicio, mi.material_id,
                   CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS responsable
            FROM mantenimiento m
            JOIN mantenimiento_inventario mi ON m.mantenimiento_id = mi.mantenimiento_id
            JOIN inventario i ON mi.material_id = i.material_id
            JOIN personales p ON m.personal_id = p.personal_id
            WHERE i.estatus_id = ? AND m.fecha_final IS NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $estado);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}


function obtenerMaterialesDisponibles($edificio_id)
{
  $conn = Conexion::get_connection();
  $query = "SELECT i.material_id, i.serie, i.modelo, t.nombre AS tipo_material 
              FROM inventario i
              JOIN tipo_material t ON i.tipo_material_id = t.tipo_material_id
              WHERE i.estatus_id = 1 AND i.edificio_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $edificio_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}

function insertarMantenimiento($descripcion, $personal_id, $material_id)
{
  $conn = Conexion::get_connection();
  $fecha_inicio = date('Y-m-d');
  $query = "INSERT INTO mantenimiento (descripcion, fecha_inicio, fecha_final, notas, personal_id) VALUES (?, ?, NULL, NULL, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssi", $descripcion, $fecha_inicio, $personal_id);
  if ($stmt->execute()) {
    $mantenimiento_id = $stmt->insert_id;
    $query = "INSERT INTO mantenimiento_inventario (mantenimiento_id, material_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $mantenimiento_id, $material_id);
    return $stmt->execute();
  }
  return false;
}

function finalizarMantenimiento($mantenimiento_id, $notas)
{
  $conn = Conexion::get_connection();
  $fecha_final = date('Y-m-d');
  $query = "UPDATE mantenimiento SET fecha_final = ?, notas = ? WHERE mantenimiento_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssi", $fecha_final, $notas, $mantenimiento_id);
  return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['accion']) && $_POST['accion'] == 'insertar') {
    $descripcion = $_POST['descripcion'];
    $material_id = $_POST['material_id'];
    $estatus = obtenerEstatusMaterial($material_id);

    if ($estatus == 1) {  // Si el material está disponible
      if (insertarMantenimiento($descripcion, $personal_id, $material_id)) {
        actualizarEstatusMaterial($material_id, 3);  // Cambiar estatus a 'En mantenimiento'
        header("Location: mantenimiento.php");
        exit();
      } else {
        echo "Error al insertar mantenimiento.";
      }
    } else {
      echo "El material no está disponible para mantenimiento.";
    }
  } else if (isset($_POST['accion']) && $_POST['accion'] == 'finalizar') {
    $mantenimiento_id = $_POST['mantenimiento_id'];
    $notas = $_POST['notas'];
    if (finalizarMantenimiento($mantenimiento_id, $notas)) {
      $material_id = obtenerMaterialIdPorMantenimiento($mantenimiento_id);
      actualizarEstatusMaterial($material_id, 1);  // Cambiar estatus a 'Disponible'
      header("Location: mantenimiento.php");
      exit();
    } else {
      echo "Error al finalizar mantenimiento.";
    }
  }
}

$mantenimientosPendientes = obtenerMantenimientos(3);
$materialesDisponibles = obtenerMaterialesDisponibles($edificio_id);

function obtenerEstatusMaterial($material_id)
{
  $conn = Conexion::get_connection();
  $query = "SELECT estatus_id FROM inventario WHERE material_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $material_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc()['estatus_id'];
}

function actualizarEstatusMaterial($material_id, $estatus)
{
  $conn = Conexion::get_connection();
  $query = "UPDATE inventario SET estatus_id = ? WHERE material_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $estatus, $material_id);
  return $stmt->execute();
}

function obtenerMaterialIdPorMantenimiento($mantenimiento_id)
{
  $conn = Conexion::get_connection();
  $query = "SELECT material_id FROM mantenimiento_inventario WHERE mantenimiento_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $mantenimiento_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc()['material_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mantenimiento</title>
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/personal_mantenimiento.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/pm_modal.js"></script>
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <h1 class="app-title">CISTA</h1>
        </div>
        <div class="profile">
            <img class="user-avatar" src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Avatar">
            <h3 class="titleName"><?php echo $_SESSION['fullname']; ?></h3>
            <p class="titleMail"><?php echo $_SESSION['correo']; ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="/WMS2/LandingPage/html/personal/indice/index.php"><label class="linkLabel">Home</label></a></li>
                <li class="dropdown">
                    <span class="dropdown-toggle">Formularios</span>
                    <ul class="dropdown-menu">
                        <li><a href="/WMS2/LandingPage/formularios/mantenimiento.php">Mantenimiento</a></li>
                        <li><a href="/WMS2/LandingPage/formularios/personal_prestamos.php">Préstamos</a></li>
                        <li><a href="/WMS2/LandingPage/formularios/transacciones.php">Transacciones</a></li>
                    </ul>
                </li>
                <li><a href="/WMS2/LandingPage/html/personal/users/users.php"><label class="linkLabel">Usuarios</label></a></li>
                <li><a href="/WMS2/LandingPage/html/personal/history/history.php"><label class="linkLabel">Historiales</label></a></li>
                <li><a href="/WMS2/LandingPage/phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <section class="content">
            <h2>Registro de Mantenimiento</h2>
            <form method="POST" action="mantenimiento.php">
                <input type="hidden" name="accion" value="insertar">
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="material_id">Material:</label>
                    <select name="material_id" id="material_id" required>
                        <option value="">Seleccione un material</option>
                        <?php foreach ($materialesDisponibles as $material): ?>
                            <option value="<?= $material['material_id'] ?>">
                                <?= $material['tipo_material'] ?> - <?= $material['serie'] ?> - <?= $material['modelo'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                  <button type="submit">Registrar Mantenimiento</button>
            </form>

            <h2>Mantenimientos Pendientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha de Inicio</th>
                        <th>Responsable</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mantenimientosPendientes as $mantenimiento): ?>
                        <tr>
                            <td><?= $mantenimiento['mantenimiento_id'] ?></td>
                            <td><?= $mantenimiento['descripcion'] ?></td>
                            <td><?= $mantenimiento['fecha_inicio'] ?></td>
                            <td><?= $mantenimiento['responsable'] ?></td>
                            <td class="table-buttons">
                                <button class="finalizar-mantenimiento-btn" data-mantenimiento-id="<?= $mantenimiento['mantenimiento_id'] ?>">Finalizar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modal de Finalización -->
    <div id="finalizacionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Finalizar Mantenimiento</h3>
            <form method="POST" action="mantenimiento.php">
                <input type="hidden" name="accion" value="finalizar">
                <input type="hidden" name="mantenimiento_id" id="finalizacionMantenimientoId">
                <div class="form-group">
                    <label for="finalizacionNotas">Notas:</label>
                    <textarea name="notas" id="finalizacionNotas" required></textarea>
                </div>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>
