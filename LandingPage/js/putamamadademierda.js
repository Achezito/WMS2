// Función para abrir el pop-up
function abrirPopup(operacion_id, estatus) {
    if (estatus !== 'pendiente') {
        alert("Solo puedes abrir préstamos con estatus 'pendiente'.");
        return;
    }

    console.log("Operación ID enviada: ", operacion_id);
    var contenido = document.getElementById("popup-detalles");
    contenido.innerHTML = `
        <div class="popup-header">
            <h2>Operación ID: ${operacion_id}</h2>
            <p>¿Qué deseas hacer?</p>
        </div>
        <div class="options">
            <button class="button delete-btn" onclick="eliminar(${operacion_id})">Eliminar</button>
            <button class="button modify-btn" onclick="modificar(${operacion_id})">Modificar</button>
        </div>
        <div class="popup-footer">
            <button class="button close-btn" onclick="cerrarPopup()">Cerrar</button>
        </div>
    `;

    // Mostrar el pop-up
    document.getElementById("popup").style.display = "flex";
}

// Función para cerrar el pop-up
function cerrarPopup() {
    document.getElementById("popup").style.display = "none";
}

// Función para eliminar el préstamo
function eliminar(operacion_id) {
    if (confirm("¿Estás seguro de que deseas eliminar este préstamo?")) {
        fetch(`../../phpFiles/config/api_prestamo.php?action=delete&operacion_id=${operacion_id}`, {
            method: 'DELETE',
        })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del servidor:", data);
                if (data.success) {
                    alert("Préstamo eliminado exitosamente");
                    cerrarPopup(); // Cerrar el popup después de una eliminación exitosa
                    // Aquí podrías actualizar la interfaz para reflejar la eliminación
                } else {
                    console.error("Error en el servidor:", data.error);
                    alert("Error al eliminar el préstamo: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error en la solicitud Fetch:", error);
                alert("Error al procesar la solicitud.");
            });
    }
}

// Función para modificar el préstamo
function modificar(operacion_id) {
    console.log("Operación ID enviada: ", operacion_id);
    var popupModify = document.getElementById("popupModify");

    // Llamada AJAX con Fetch API para obtener los detalles del préstamo y los materiales disponibles
    console.log(`../../phpFiles/config/api_prestamo.php?action=get&operacion_id=${operacion_id}`);

    fetch(`../../phpFiles/config/api_prestamo.php?action=get&operacion_id=${operacion_id}`)
        .then(response => response.json())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.error) {
                popupModify.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                const prestamo = data.prestamo;
                const materiales = data.materiales;
                const materialIds = Array.isArray(prestamo.material_id) ? prestamo.material_id : [];

                // Aquí generas el HTML del formulario
                popupModify.innerHTML = `
<h3>Modificar Préstamo: ${operacion_id}</h3>
<form id="formModificar">
    <label for="notas">Notas:</label>
    <textarea placeholder="Escribe tu comentario aquí" rows="4" cols="50" style="resize: none;" id="notas" name="notas" required>${prestamo.notas}</textarea>

    <label for="material">Selecciona Material(es):</label>
    <select id="material" name="material[]" multiple required>
        <option value="">Selecciona uno o más materiales</option>
        ${materiales.map(material => {
                    const selected = materialIds.includes(material.material_id) ? 'selected' : '';
                    return `<option value="${material.material_id}" ${selected}>
                ${material.modelo} (${material.tipo_material})
            </option>`;
                }).join('')}
    </select>

    <button type="button" class="guardar" onclick="guardarDatos(${prestamo.prestamo_id})">Guardar Cambios</button>
</form>
`;

                // Verifica que el select con id="material" esté presente
                console.log(document.getElementById("material"));

                // Abre el modal
                popupModify.style.display = 'flex';
                document.getElementById("popup").scrollTo(0, 0);

                // Inicializa Select2 después de actualizar el HTML
                // Inicializa Select2 con los estilos y personalización deseada
                $('#material').select2({
                    placeholder: "Selecciona uno o más materiales",
                    allowClear: true
                }).css({
                    "font-size": "14px",
                    "padding": "10px"
                });

            }
        })
        .catch(error => {
            console.error("Error al obtener los detalles del préstamo:", error);
            popupModify.innerHTML = `<p>Error al obtener los datos. Inténtalo nuevamente.</p>`;
        });
}


// Función para guardar los datos modificados
function guardarDatos(prestamoId) {
    const notas = document.getElementById("notas").value;
    const materialesSeleccionados = Array.from(document.getElementById("material").selectedOptions).map(option => option.value);

    const data = {
        prestamo_id: prestamoId,
        notas: notas,
        material_ids: materialesSeleccionados
    };

    fetch(`../../phpFiles/config/api_prestamo.php?action=update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si la respuesta es exitosa, actualiza la interfaz de usuario
                alert('Préstamo actualizado correctamente');
                actualizarInterfaz(prestamoId, data.prestamo);
                popupModify.style.display = 'none';  // Cerrar el popup
            } else {
                alert('Error al actualizar el préstamo: ' + data.error);
            }
        })
        .catch(error => {
            console.error("Error al guardar los cambios:", error);
            alert("Hubo un error al guardar los cambios. Inténtalo nuevamente.");
        });
}

function actualizarInterfaz(prestamoId, prestamoActualizado) {
    // Aquí actualizas el área de la UI donde se muestran los datos del préstamo.
    // Por ejemplo, si tienes una lista de préstamos, puedes actualizar la entrada de ese préstamo.
    const prestamoElemento = document.getElementById(`prestamo-${prestamoId}`);
    if (prestamoElemento) {
        prestamoElemento.querySelector('.notas').textContent = prestamoActualizado.notas;

        const materialesSelect = prestamoElemento.querySelector('.materiales');
        materialesSelect.innerHTML = prestamoActualizado.materiales.map(material => {
            return `<li>${material.modelo} (${material.tipo_material})</li>`;
        }).join('');
    }
}
