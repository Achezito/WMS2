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
let tableIndex = []; // Índice para almacenar las filas y su contenido

/**
 * Función para construir el índice de la tabla.
 * Recorre las filas de la tabla y almacena el contenido en un array.
 */
function buildIndex() {
    const rows = document.querySelectorAll('table tbody tr'); // Filas del cuerpo de la tabla
    tableIndex = Array.from(rows).map(row => ({
        row, // Referencia a la fila
        text: row.textContent.toLowerCase(), // Texto de la fila en minúsculas
    }));
}

/**
 * Función para filtrar la tabla según el texto ingresado.
 */
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();

    tableIndex.forEach(({ row, text }) => {
        row.style.display = text.includes(input) ? '' : 'none'; // Mostrar/ocultar fila
    });
}

/**
 * Función debounce para retrasar la ejecución de la búsqueda.
 * @param {Function} func - La función a ejecutar.
 * @param {number} delay - El retraso en milisegundos.
 * @returns {Function}
 */
function debounce(func, delay) {
    let debounceTimeout;
    return (...args) => {
        clearTimeout(debounceTimeout); // Limpiar el temporizador anterior
        debounceTimeout = setTimeout(() => func(...args), delay); // Iniciar nuevo temporizador
    };
}

// Construir el índice una vez cargado el DOM
document.addEventListener('DOMContentLoaded', () => {
    buildIndex();

    // Asociar evento input con la función filtrada mediante debounce
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', debounce(filterTable, 300));
});

function onClickRow(materialid) {
    // Abrir una nueva pestaña (o ventana) con la URL deseada
    window.open(`../html/personal/inventario/materialDetails.php?material_id=${materialid}`, '_blank');
}


