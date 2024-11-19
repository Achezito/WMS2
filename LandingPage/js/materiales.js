document.addEventListener('DOMContentLoaded', function () {
    // Función para ordenar la tabla por una columna
    function sortTable(n) {
        const table = document.querySelector('table');
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc"; // Set the sorting direction to ascending:
        
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    // Añadir evento de clic para ordenar columnas
    const headers = document.querySelectorAll("table th");
    headers.forEach((header, index) => {
        header.addEventListener("click", function() {
            sortTable(index);
        });
    });
});
function filterTable() {
    // Obtener el valor del input de búsqueda
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase(); // Convertir a minúsculas para búsqueda insensible a mayúsculas
    const table = document.querySelector('table');
    const rows = table.getElementsByTagName('tr'); // Obtener todas las filas de la tabla

    // Recorrer las filas de la tabla (empezamos desde la fila 1 para omitir el encabezado)
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let rowContainsFilterText = false;

        // Recorrer las celdas de cada fila
        for (let j = 0; j < cells.length; j++) {
            // Si alguna celda contiene el texto de búsqueda, mostramos la fila
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                rowContainsFilterText = true;
                break; // No necesitamos seguir buscando en otras celdas de la fila
            }
        }

        // Mostrar u ocultar la fila según si contiene el texto de búsqueda
        if (rowContainsFilterText) {
            rows[i].style.display = ''; // Mostrar la fila
        } else {
            rows[i].style.display = 'none'; // Ocultar la fila
        }
    }
}

function onClickRow(materialid) {
    // Abrir una nueva pestaña (o ventana) con la URL deseada
    window.open(`/WMS2/LandingPage/html/personal/inventario/materialDetails.php?material_id=${materialid}`, '_blank');
}


