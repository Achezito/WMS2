<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/editMyProfile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>

</head>
<body>
  <div class="profile-container">
    <div class="profile-header">
      <h1>Editar Perfil</h1>
      <button onclick="goBack()">Volver a Mi Perfil</button>
    </div>

    <form action="#" method="POST" class="edit-form">
      <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" value="Juan Pérez" required>
      </div>

      <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" value="juan@example.com" required>
      </div>

      <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="tel" id="phone" name="phone" value="+123 456 7890" required>
      </div>

      <div class="form-group">
        <label for="location">Ubicación</label>
        <input type="text" id="location" name="location" value="Ciudad de México, México" required>
      </div>

      <div class="form-group">
        <label for="linkedin">LinkedIn</label>
        <input type="url" id="linkedin" name="linkedin" value="https://linkedin.com" required>
      </div>

      <div class="form-group">
        <label for="github">GitHub</label>
        <input type="url" id="github" name="github" value="https://github.com" required>
      </div>

      <div class="form-actions">
        <button type="submit">Guardar Cambios</button>
        <button type="button" onclick="cancelEdit()">Cancelar</button>
      </div>
    </form>
  </div>

  <script>
    function goBack() {
      window.history.back(); // Regresa al perfil anterior
    }

    function cancelEdit() {
      // Aquí puedes agregar la lógica para cancelar y regresar a la página de perfil
      alert('Cambios no guardados.');
      goBack();
    }
  </script>
</body>
</html>
