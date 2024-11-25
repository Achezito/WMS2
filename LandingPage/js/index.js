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

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase(); // Convertir el valor del input a minúsculas
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody'); // Seleccionar solo las filas del cuerpo de la tabla
    const rows = tbody.getElementsByTagName('tr');

    // Recorrer las filas del cuerpo de la tabla
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let rowContainsFilterText = false;

        // Recorrer las celdas de la fila
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                rowContainsFilterText = true;
                break;
            }
        }

        // Mostrar u ocultar la fila según el filtro
        rows[i].style.display = rowContainsFilterText ? '' : 'none';
    }
}

function onClickRow(materialId) {
    console.log('Fila clicada con ID:', materialId);
}


