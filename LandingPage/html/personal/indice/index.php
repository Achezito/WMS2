<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';

// Límite de inactividad en segundos
$limite_inactividad = 100000;

// Obtener los tipos de materiales
$tipos_materiales = inventario::obtenerTiposMateriales();

// Verificar el tiempo de inactividad
if (isset($_SESSION['ultimo_acceso'])) {
  $inactividad = time() - $_SESSION['ultimo_acceso'];

  // Si el tiempo de inactividad supera el límite, cerrar sesión y redirigir
  if ($inactividad > $limite_inactividad) {
    session_unset();
    session_destroy();
    header("Location: ../../html/login.php?sesion=expirada");
    exit();
  }
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
  header('Location: ../../../index.php');
  exit();
}

if (isset($_SESSION['edificio_id'])) {
  $edificio_id = $_SESSION['edificio_id'];
  $materiales = Inventario::obtenerMaterialesPorEdificio($edificio_id);
} else {
  echo "Error: No se ha asignado un edificio al usuario actual.";
  exit();
}

// Manejar la actualización del material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_material'])) {
  $material_id = $_POST['material_id'];
  $serie = $_POST['serie'];
  $modelo = $_POST['modelo'];
  $tipo_material_id = $_POST['tipo_material'];

  $resultado = inventario::actualizarMaterial($material_id, $serie, $modelo, $tipo_material_id);

  if ($resultado) {
    $mensaje = "Material actualizado correctamente.";
  } else {
    $mensaje = "Error al actualizar el material.";
  }

  // Redirigir para evitar la reenvío del formulario al refrescar
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Principal</title>
  <link rel="stylesheet" href="../../../css/index.css">
  <link rel="stylesheet" href="../../../css/index2.css">
  <link rel="stylesheet" href="../../../css/hom2.css">
  <link rel="stylesheet" href="../../../css/materials.css">
  <link rel="stylesheet" href="../../../css/index_modal.css">
  <script src="../../../js/index.js"></script>
</head>

<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
      <div class="logo-container">
        <h1 class="app-title">CISTA</h1>
      </div>
      <div class="profile">
        <img class="user-avatar" src="../../../img/Users/User.jpg" alt="User Avatar">
        <h3 class="titleName"><?php echo $_SESSION['fullname']; ?></h3>
        <p class="titleMail"><?php echo $_SESSION['correo']; ?></p>
      </div>
      <nav>
        <ul>
          <li><a href="../../../html/personal/indice/index.php"><label class="linkLabel">Home</label></a></li>
          <li class="dropdown">
            <span class="dropdown-toggle">Formularios</span>
            <ul class="dropdown-menu">
              <li><a href="../../../formularios/personal_prestamos.php">Préstamos</a></li>
              <li><a href="../../../formularios/transacciones.php">Transacciones</a></li>
              <li><a href="../../../formularios/mantenimiento.php">Mantenimiento</a></li>
            </ul>
          </li>
          <li><a href="../../../html/personal/users/users.php"><label class="linkLabel">Usuarios</label></a></li>
          <li><a href="../../../html/personal/history/history.php"><label class="linkLabel">Historiales</label></a></li>
          <li><a href="../../../phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
        </ul>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
      <section class="content">
        <div class="header-container">
          <h2><?php echo htmlspecialchars($materiales[0]['edificio']); ?></h2>
          <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">
        </div>
        <div class="scrollable-table">
          <?php
          if (!empty($materiales)) {
            echo "<table border='1'>";
            // Encabezado del edificio
            echo "<thead>";
            echo "<tr>
                    <th>ID Material</th>
                    <th>Serie</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>";
            echo "</thead>";

            // Cuerpo de la tabla
            echo "<tbody>";
            foreach ($materiales as $material) {
              // Determinar la clase CSS según el estatus
              $estatus_class = '';
              switch (trim($material['estatus'])) {
                case 'Disponible':
                  $estatus_class = 'disponible';
                  break;
                case 'En uso':
                  $estatus_class = 'enUso';
                  break;
                case 'En mantenimiento':
                  $estatus_class = 'enMantenimiento';
                  break;
                case 'Fuera de servicio':
                  $estatus_class = 'fueraDeServicio';
                  break;
              }

              echo "<tr data-id='" . htmlspecialchars($material['material_id']) . "'>";
              echo "<td>" . htmlspecialchars($material['material_id']) . "</td>";
              echo "<td>" . htmlspecialchars($material['serie']) . "</td>";
              echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
              echo "<td data-tipo-id='" . htmlspecialchars($material['tipo_material_id']) . "'>" . htmlspecialchars($material['tipo_material']) . "</td>";
              echo "<td class='estatus'><span class='$estatus_class'>" . htmlspecialchars($material['estatus']) . "</span></td>";
              echo "<td><button class='edit-button'>Editar</button></td>";
              echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
          } else {
            echo "No hay materiales vinculados a tu edificio.";
          }
          ?>
        </div>
      </section>
    </main>
  </div>

  <?php if (isset($mensaje)) { echo "<p>$mensaje</p>"; } ?>

  <!-- Modal para editar material -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h2>Editar Material</h2>
      <form id="editForm" method="POST" action="">
        <input type="hidden" id="materialId" name="material_id">
        <label for="serieInput">Serie:</label>
        <input type="text" id="serieInput" name="serie">
        <label for="modeloInput">Modelo:</label>
        <input type="text" id="modeloInput" name="modelo">
        <label for="tipoInput">Tipo:</label>
        <select id="tipoInput" name="tipo_material">
          <?php foreach ($tipos_materiales as $tipo): ?>
            <option value="<?php echo $tipo['tipo_material_id']; ?>"><?php echo $tipo['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" name="update_material">Guardar Cambios</button>
      </form>
    </div>
  </div>
  <script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("table tbody tr");
      rows.forEach(row => {
        const cells = Array.from(row.children);
        const match = cells.some(cell => cell.textContent.toLowerCase().includes(filter));
        row.style.display = match ? "" : "none";
      });
    });
  </script>
</body>

</html>
