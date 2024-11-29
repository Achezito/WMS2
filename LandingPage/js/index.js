document.addEventListener('DOMContentLoaded', function () {
    init();
});

function init() {
    // Inicialización para el ícono de cerrar sesión
    const logoutIcon = document.getElementById('logout-icon');
    if (logoutIcon) {
        logoutIcon.addEventListener('click', function () {
            // Redirige al login.html cuando se hace clic
            window.location.href = 'login.html'; // Ajusta la ruta si es necesario
        });
    } else {
        console.log('No se encontró el ícono de cerrar sesión');
    }

    // Inicialización para los botones de editar
    const editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const id = row.getAttribute('data-id');
            const serie = row.children[1].textContent;
            const modelo = row.children[2].textContent;
            const tipo = row.children[3].getAttribute('data-tipo-id');

            document.getElementById('materialId').value = id;
            document.getElementById('serieInput').value = serie;
            document.getElementById('modeloInput').value = modelo;
            document.getElementById('tipoInput').value = tipo;

            document.getElementById('editModal').style.display = 'block';
        });
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('editModal').style.display = 'none';
    });
}

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

function filterPrestamos() {
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
