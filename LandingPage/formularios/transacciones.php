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

    // Insertar un nuevo proveedor si es necesario
    if ($proveedor_id == 'nuevo') {
        $nombre = $_POST['nuevo_proveedor_nombre'];
        $telefono = $_POST['nuevo_proveedor_telefono'];
        $correo = $_POST['nuevo_proveedor_correo'];
        $proveedor_id = insertarProveedor($nombre, $telefono, $correo);
    }

    $transaccion_id = insertarTransaccion($tipo_transaccion, $notas);

    if ($tipo_transaccion === 'salida') {
        $materiales_seleccionados = $_POST['materiales'];
        foreach ($materiales_seleccionados as $material_id) {
            actualizarEstatusMaterial($material_id, 4); // Cambiar estatus a 'Fuera de servicio'
            insertarInventarioTransaccion($transaccion_id, $material_id, $personal_id, $proveedor_id);
        }
    } else if ($tipo_transaccion === 'entrada') {
        // Insertar nuevos tipos de material si es necesario
        $tipo_material_id = $_POST['tipo_material'];
        if ($tipo_material_id == 'nuevo_tipo') {
            $nombre_tipo = $_POST['nuevo_tipo_material_nombre'];
            $categoria = $_POST['nuevo_tipo_material_categoria'];
            $descripcion = $_POST['nuevo_tipo_material_descripcion'];
            $tipo_material_id = insertarTipoMaterial($nombre_tipo, $categoria, $descripcion);
        }

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

    header('Location: transacciones.php');
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
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/inventario_transaccion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/inventario_transaccion.js"></script>
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
                <form method="POST" action="">
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
                </form>
            </div>
        </section>
    </main>
</body>
</html>