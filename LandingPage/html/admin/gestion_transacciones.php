<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/Models/tipo_material.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'administrador') {
  header('Location: ../../html/login.php');
  exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos
if (isset($_SESSION['ultimo_acceso'])) {
  $inactividad = time() - $_SESSION['ultimo_acceso'];
  if ($inactividad > $limite_inactividad) {
    session_unset();
    session_destroy();
    header("Location: ../../html/login.php?sesion=expirada");
    exit();
  }
}
$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso



// Verificar si el edificio está asignado
if (isset($_GET['id'])) {
  $edificio_id = $_GET['id'];
  $_SESSION['edificio_id'] = $edificio_id; // Almacenar el ID del edificio en la sesión
  $edificios = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
} else {
  // Si no hay edificio asignado, verifica si ya está en la sesión
  if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
    $edificios = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
    $materiales = Inventario::obtenerMaterialesPorEdificio($edificio_id);
  } else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
  }
}
$proveedores = obtenerProveedores();
$tiposMaterial = obtenerTiposMaterial();
$materialesDisponibles = obtenerMaterialesDisponibles($edificio_id);



function obtenerProveedores() {
  $conn = Conexion::get_connection();
  $query = "SELECT * FROM proveedores";
  $result = $conn->query($query);
  return $result->fetch_all(MYSQLI_ASSOC);
}

function obtenerTiposMaterial() {
  $conn = Conexion::get_connection();
  $query = "SELECT * FROM tipo_material";
  $result = $conn->query($query);
  return $result->fetch_all(MYSQLI_ASSOC);
}

function obtenerMaterialesDisponibles($edificio_id) {
  $conn = Conexion::get_connection();
  $query = "SELECT material_id, serie, modelo FROM inventario WHERE edificio_id = ? AND estatus_id = 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $edificio_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}

function insertarTransaccion($tipo_transaccion, $notas) {
  $conn = Conexion::get_connection();
  $fecha_inicio = date('Y-m-d');
  $fecha_final = ($tipo_transaccion === 'salida') ? $fecha_inicio : null;

  $query = "INSERT INTO transacciones (tipo_transaccion, fecha_inicio, fecha_final, notas) 
            VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssss", $tipo_transaccion, $fecha_inicio, $fecha_final, $notas);
  $stmt->execute();
  return $stmt->insert_id;
}

function insertarMaterial($serie, $modelo, $tipo_material, $edificio_id, $estatus_id) {
  $conn = Conexion::get_connection();
  $query = "INSERT INTO inventario (serie, modelo, tipo_material_id, edificio_id, estatus_id) 
            VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssiii", $serie, $modelo, $tipo_material, $edificio_id, $estatus_id);
  $stmt->execute();
  return $stmt->insert_id;
}

function insertarInventarioTransaccion($transaccion_id, $material_id, $personal_id, $proveedor_id) {
  $conn = Conexion::get_connection();
  $query = "INSERT INTO inventario_transaccion (transaccion_id, material_id, personal_id, proveedor_id) 
            VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iiii", $transaccion_id, $material_id, $personal_id, $proveedor_id);
  $stmt->execute();
}

function actualizarEstatusMaterial($material_id, $estatus_id) {
  $conn = Conexion::get_connection();
  $query = "UPDATE inventario SET estatus_id = ? WHERE material_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $estatus_id, $material_id);
  $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');
  $response = ['success' => false, 'message' => ''];

  try {
      $tipo_transaccion = $_POST['tipo_transaccion'];
      $proveedor_id = $_POST['proveedor'];
      $notas = $_POST['notas'];

      // Insertar un nuevo proveedor si es necesario
      if ($proveedor_id === 'nuevo') {
          $nombre = $_POST['nuevo_proveedor_nombre'];
          $telefono = $_POST['nuevo_proveedor_telefono'];
          $correo = $_POST['nuevo_proveedor_correo'];
          $proveedor_id = insertarProveedor($nombre, $telefono, $correo);

          if (!$proveedor_id) {
              throw new Exception("Error al insertar el nuevo proveedor.");
          }
      }

      // Insertar transacción
      $transaccion_id = insertarTransaccion($tipo_transaccion, $notas);

      if (!$transaccion_id) {
          throw new Exception("Error al insertar la transacción.");
      }

      if ($tipo_transaccion === 'salida') {
          $materiales_seleccionados = $_POST['materiales'];
          foreach ($materiales_seleccionados as $material_id) {
              $estatus_actualizado = actualizarEstatusMaterial($material_id, 4);
              if (!$estatus_actualizado) {
                  throw new Exception("Error al actualizar el estatus del material con ID $material_id.");
              }
              $inventario_transaccion = insertarInventarioTransaccion($transaccion_id, $material_id, 9, $proveedor_id);
              if (!$inventario_transaccion) {
                  throw new Exception("Error al insertar en inventario-transacción para el material con ID $material_id.");
              }
          }
      }

      $response['success'] = true;
      $response['message'] = "Operación completada con éxito.";
  } catch (Exception $e) {
      $response['message'] = $e->getMessage();
  }

  echo json_encode($response);
  exit;
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Principal</title>
  <link rel="stylesheet" href="../../css/index.css">
  <link rel="stylesheet" href="../../css/index2.css">
  <link rel="stylesheet" href="../../css/hom2.css">
  <link rel="stylesheet" href="../../css/materials.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="../../js/index.js"></script>
  <script src="../../js/inventario_transaccion.js"></script>
  <link rel="stylesheet" href="../../css/inventario_transaccion.css">
</head>

<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
      <div class="logo-container">
        <h1 class="app-title">CISTA</h1>
      </div>
      <div class="profile">
        <img class="user-avatar" src="../../img/Users/User.jpg" alt="User Avatar">
        <h3 class="titleName">
          <?php echo $_SESSION['username']; ?>
        </h3>
        <p class="titleMail"> <?php echo $_SESSION['username']; ?></p>
      </div>
      <nav>
        <ul>
          <li><a href="../../html/admin/indexAdmin.php"><label class="linkLabel">Home</label></a></li>
          <li><a href="../../html/admin/gestion_inventario.php"><label class="linkLabel">Gestión de Inventario</label></a></li>
          <li><a href="../../html/admin/gestionar_usuarios.php"><label class="linkLabel">Gestión de Usuarios</label></a></li>
          <li><a href="../../html/admin/gestion_prestamos.php"><label class="linkLabel">Gestión de Préstamos</label></a></li>
          <li><a href="../../phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
        </ul>
      </nav>
    </aside>

    <!-- Contenido principal -->
     <main class="main-content">
        <section class="content">
            <div class="form-container">
                <h2>Registro de Transacción</h2>
                <form id="form">
                    <div class="form-group">
                        <label for="tipo_transaccion">Tipo de Transacción:</label>
                        <select name="tipo_transaccion" id="tipo_transaccion" required>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="proveedor">Proveedor:</label>
                        <select name="proveedor" id="proveedor" required>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <option value="<?= $proveedor['proveedor_id'] ?>"><?= $proveedor['nombre'] ?></option>
                            <?php endforeach; ?>
                            <option value="nuevo">Agregar nuevo proveedor</option>
                        </select>
                    </div>
                    <div id="nuevoProveedor" style="display:none;">
                        <div class="form-group">
                            <label for="nuevo_proveedor_nombre">Nombre:</label>
                            <input type="text" id="nuevo_proveedor_nombre" name="nuevo_proveedor_nombre">
                        </div>
                        <div class="form-group">
                            <label for="nuevo_proveedor_telefono">Teléfono:</label>
                            <input type="text" id="nuevo_proveedor_telefono" name="nuevo_proveedor_telefono">
                        </div>
                        <div class="form-group">
                            <label for="nuevo_proveedor_correo">Correo:</label>
                            <input type="email" id="nuevo_proveedor_correo" name="nuevo_proveedor_correo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notas">Notas:</label>
                        <textarea name="notas" id="notas" rows="4"></textarea>
                    </div>
                    <div id="entradaMateriales">
                        <div class="form-group">
                            <label for="modelo_material">Modelo del Material:</label>
                            <input type="text" id="modelo_material" name="modelo_material">
                        </div>
                        <div class="form-group">
                            <label for="tipo_material">Tipo de Material:</label>
                            <select name="tipo_material" id="tipo_material">
                                <?php foreach ($tiposMaterial as $tipo): ?>
                                    <option value="<?= $tipo['tipo_material_id'] ?>"><?= $tipo['nombre'] ?></option>
                                <?php endforeach; ?>
                                <option value="nuevo_tipo">Agregar nuevo tipo de material</option>
                            </select>
                        </div>
                        <div id="nuevoTipoMaterial" style="display:none;">
                            <div class="form-group">
                                <label for="nuevo_tipo_material_nombre">Nombre:</label>
                                <input type="text" id="nuevo_tipo_material_nombre" name="nuevo_tipo_material_nombre">
                            </div>
                            <div class="form-group">
                                <label for="nuevo_tipo_material_categoria">Categoría:</label>
                                <input type="text" id="nuevo_tipo_material_categoria" name="nuevo_tipo_material_categoria">
                            </div>
                            <div class="form-group">
                                <label for="nuevo_tipo_material_descripcion">Descripción:</label>
                                <textarea id="nuevo_tipo_material_descripcion" name="nuevo_tipo_material_descripcion" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_material">Cantidad de Materiales:</label>
                            <input type="number" id="cantidad_material" name="cantidad_material" min="1">
                            <button type="button" id="agregarMaterial">Agregar Material</button>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <table id="materialesTable">
                                    <thead>
                                        <tr>
                                            <th>Serie</th>
                                            <th>Modelo</th>
                                            <th>Tipo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se generarán los templates de materiales -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="salidaMateriales" style="display:none;">
                        <div class="form-group">
                            <label for="materialesDisponibles">Materiales Disponibles:</label>
                            <select id="materialesDisponibles">
                                <?php foreach ($materialesDisponibles as $material): ?>
                                    <option value="<?= $material['material_id'] ?>"><?= $material['serie'] ?> - <?= $material['modelo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" id="agregarMaterialSalida">Agregar Material</button>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <table id="agregarMaterialesTable">
                                    <thead>
                                        <tr>
                                            <th>Serie</th>
                                            <th>Modelo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se generarán los materiales seleccionados para salida -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button type="submit">Registrar Transacción</button>
                    <div id="messageContainer" class="message"></div>

                </form>
            </div>
        </section>
    </main>
</body>
</html>