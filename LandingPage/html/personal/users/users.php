<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
  header('location: ../../../html/login.php');
  exit();
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

if (isset($_SESSION['edificio_id'])) {
  $edificio_id = $_SESSION['edificio_id'];
  $usuarios = Usuario::obtenerUsuarios($edificio_id);
} else {
  echo "Error: No se ha asignado un edificio al usuario actual.";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historiales</title>
  <link rel="stylesheet" href="../../../css/index.css">
  <link rel="stylesheet" href="../../../css/index2.css">
  <link rel="stylesheet" href="../../../css/users.css">
  <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet" />
  <link href="../../../css/fontawesome/solid.css" rel="stylesheet" />
  <script src="../../../js/index.js"></script>
  <script src="../../../js/usuarios.js"></script>
  <title>Principal</title>
  <link rel="stylesheet" href="../../../css/hom2.css">
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
        <img class="user-avatar" src="../../../img/Users/User.jpg" alt="User Avatar">
        <h3 class="titleName">
          <?php echo $_SESSION['fullname']; ?>
        </h3>
        <p class="titleMail">
          <?php echo $_SESSION['correo']; ?>
        </p>
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
        <h2>Usuarios</h2>
        <div class="header-container">
          <input type="text" id="searchInput" placeholder="Buscar...">
        </div>

        <?php if (!empty($usuarios)) : ?>
          <div class="scrollable-table">
            <table>
              <thead>
                <tr>
                  <th>ID Usuario</th>
                  <th>Nombre</th>
                  <th>Fecha de Creación</th>
                  <th>Estado</th>
                  <th>Correo</th>
                  <th>ID Edificio</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                  <tr onclick='onClickRow(<?php echo $usuario->getUsuarioId(); ?>)'>
                    <td><?php echo $usuario->getUsuarioId(); ?></td>
                    <td><?php echo $usuario->getNombre(); ?></td>
                    <td><?php echo $usuario->getFechaCreacion(); ?></td>
                    <td><?php echo $usuario->getEstado(); ?></td>
                    <td><?php echo $usuario->getCorreo(); ?></td>
                    <td><?php echo $usuario->getEdificioId(); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else : ?>
          <p>No hay usuarios disponibles.</p>
        <?php endif; ?>

      </section>
    </main>
  </div>
</body>

</html>
