document.addEventListener('DOMContentLoaded', function () {
init();
    // Selecciona el ícono de cerrar sesión
    const logoutIcon = document.getElementById('logout-icon');

    // Verifica si el ícono existe
    if (logoutIcon) {
        logoutIcon.addEventListener('click', function () {
            // Redirige al login.html cuando se hace clic
            window.location.href = 'login.html'; // Ajusta la ruta si es necesario
        });
    } else {
        console.log('No se encontró el ícono de cerrar sesión');
    }
});

