<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';

// Límite de inactividad en segundos
$limite_inactividad = 100000;

// Verificar el tiempo de inactividad
if (isset($_SESSION['ultimo_acceso'])) {
  $inactividad = time() - $_SESSION['ultimo_acceso'];

  // Si el tiempo de inactividad supera el límite, cerrar sesión y redirigir
  if ($inactividad > $limite_inactividad) {
    session_unset();
    session_destroy();
    header("Location: /WMS2/LandingPage/html/login.php?sesion=expirada");
    exit();
  }
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type'])) {
  header('location: /WMS2/LandingPage/html/login.php');
  exit();
}

if (isset($_SESSION['edificio_id'])) {
  $edificio_id = $_SESSION['edificio_id'];
  $materiales = Inventario::obtenerMaterialesPorEdificio($edificio_id);
} else {
  echo "Error: No se ha asignado un edificio al usuario actual.";
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Principal</title>
  <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
  <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
  <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
  <link rel="stylesheet" href="/WMS2/LandingPage/css/materials.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="/WMS2/LandingPage/js/index.js"></script>
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

              echo "<tr onclick='onClickRow(" . htmlspecialchars($material['material_id']) . ")'>";
              echo "<td>" . htmlspecialchars($material['material_id']) . "</td>";
              echo "<td>" . htmlspecialchars($material['serie']) . "</td>";
              echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
              echo "<td>" . htmlspecialchars($material['tipo_material']) . "</td>";
              echo "<td class='estatus'><span class='$estatus_class'>" . htmlspecialchars($material['estatus']) . "</span></td>";
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
