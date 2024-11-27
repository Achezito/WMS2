<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';

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
                <li><a href="../../html/admin/reportes.php"><label class="linkLabel">Reportes</label></a></li>
                <li><a href="../../phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
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
              </tr>";
          echo "</thead>";

          // Cuerpo de la tabla
          echo "<tbody>";
          foreach ($materiales as $material) {
            echo "<tr onclick='onClickRow(" . htmlspecialchars($material['material_id']) . ")'>";
            echo "<td>" . htmlspecialchars($material['material_id']) . "</td>";
            echo "<td>" . htmlspecialchars($material['serie']) . "</td>";
            echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['tipo_material']) . "</td>";
            echo "<td class='estatus'>" . htmlspecialchars($material['estatus']) . "</td>";
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
  <script>
    // Selecciona todas las celdas con la clase "estatus"
    document.querySelectorAll('td.estatus').forEach(cell => {
      if (cell.textContent.trim() === 'Disponible') {
        cell.classList.add('disponible');
      }
    });

    function onClickRow(materialId) {
      console.log('Fila clicada con ID:', materialId);
      // Aquí puedes agregar cualquier acción al hacer clic en una fila
    }
  </script>
</body>

</html>