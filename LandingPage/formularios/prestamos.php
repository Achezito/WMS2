<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Registro de Préstamo de Equipos</title>
</head>
<body>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()" >
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png" >
            </div>
            <h1>CISTA</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <i class="fas fa-cog"></i>
                <i class="fas fa-globe"></i>
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
            </div>
        </div>
    </header>
<div class="container">
    <h2>Registro de Préstamo de Equipos</h2>
    <form action="/registro_prestamo" method="POST">
        
        <div class="form-group">
            <label for="materialID">ID del Material:</label>
            <input type="text" id="materialID" name="materialID" required>
        </div>
        
        <div class="form-group">
            <label for="personaID">ID del Personal:</label>
            <input type="text" id="personaID" name="personaID" required>
        </div>
        
        <div class="form-group">
            <label for="fechaSalida">Fecha de Salida:</label>
            <input type="date" id="fechaSalida" name="fechaSalida" required>
        </div>
        
        <div class="form-group">
            <label for="fechaDevolucion">Fecha de Devolución:</label>
            <input type="date" id="fechaDevolucion" name="fechaDevolucion">
        </div>
        
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" required>
        </div>
        
        <div class="form-group">
            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas" rows="4"></textarea>
        </div>
        
        <form id="prestamo-form" action="/registro_prestamo" method="POST">
            <button type="submit">Registrar Préstamo</button>
        </form>
        
    </form>
</div>
<div id="menu">
    <ul>
        <li><i class="fas fa-home"></i><a href="../html/index.html"> Home</a></li>
        <li><i class="far fa-user"></i><a href="#"> My account</a></li>
        <li><i class="far fa-clipboard"></i><a href="../formularios/prestamos.html"> Préstamos </a></li>

    </ul>
</div>

<script src="../js/index.js"></script>
</body>
</html>