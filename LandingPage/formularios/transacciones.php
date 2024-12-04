<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/Models/tipo_material.php';
require_once BASE_PATH . '/phpFiles/config/conexion.php';

$personal_id = $_SESSION['personal_id'];
$edificio_id = $_SESSION['edificio_id'];

// Obtener proveedores, tipos de material y materiales disponibles
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
    $tipo_transaccion = $_POST['tipo_transaccion'];
    $proveedor_id = $_POST['proveedor'];
    $notas = $_POST['notas'];
    
    $transaccion_id = insertarTransaccion($tipo_transaccion, $notas);

    if ($tipo_transaccion === 'salida') {
        $materiales_seleccionados = $_POST['materiales'];
        foreach ($materiales_seleccionados as $material_id) {
            actualizarEstatusMaterial($material_id, 4); // Cambiar estatus a 'Fuera de servicio'
            insertarInventarioTransaccion($transaccion_id, $material_id, $personal_id, $proveedor_id);
        }
    } else if ($tipo_transaccion === 'entrada') {

        // Insertar materiales en el inventario y relacionarlos con la transacción
        $series = $_POST['series'];
        $modelos = $_POST['modelos'];
        $tipos = $_POST['tipos'];
        $estatus_id = 1;  // 'Disponible'

        foreach ($series as $index => $serie) {
            $material_id = insertarMaterial($serie, $modelos[$index], $tipos[$index], $edificio_id, $estatus_id);
            insertarInventarioTransaccion($transaccion_id, $material_id, $personal_id, $proveedor_id);
        }
    }

    // Enviar respuesta JSON al cliente
    echo json_encode(['success' => true, 'message' => 'Inventario registrado exitosamente, puedes ver los materiales en la seccion principal.']);
    exit();
}

function insertarProveedor($nombre, $telefono, $correo) {
    $conn = Conexion::get_connection();
    $query = "INSERT INTO proveedores (nombre, telefono, correo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nombre, $telefono, $correo);
    $stmt->execute();
    return $stmt->insert_id;
}

function insertarTipoMaterial($nombre, $categoria, $descripcion) {
    $conn = Conexion::get_connection();
    $query = "INSERT INTO tipo_material (nombre, categoria, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nombre, $categoria, $descripcion);
    $stmt->execute();
    return $stmt->insert_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacciones</title>
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/index2.css">
    <link rel="stylesheet" href="../css/hom2.css">
    <link rel="stylesheet" href="../css/inventario_transaccion.css">
    <script src="../js/inventario_transaccion.js"></script>
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
                        <li><a href="/WMS2/LandingPage/formularios/personal_prestamos.php">Préstamos</a></li>
                        <li><a href="/WMS2/LandingPage/formularios/transacciones.php">Transacciones</a></li>
                        <li><a href="/WMS2/LandingPage/formularios/mantenimiento.php">Mantenimiento</a></li>
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
                        </select>
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
                            </select>
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

                    <div id="messageContainer"  style="display: none;"></div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>