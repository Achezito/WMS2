// Función para abrir el pop-up
function abrirPopup(operacion_id) {
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
<div class="popup-modify" style="display:none;" id="popupModify"></div>
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
        fetch(`/WMS2/LandingPage/phpFiles/config/api_prestamo.php?action=delete&operacion_id=${operacion_id}`, {
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
    console.log(`/WMS2/LandingPage/phpFiles/config/api_prestamo.php?action=get&operacion_id=${operacion_id}`);

    fetch(`/WMS2/LandingPage/phpFiles/config/api_prestamo.php?action=get&operacion_id=${operacion_id}`)
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.error) {
            popupModify.innerHTML = `<p>Error: ${data.error}</p>`;
        } else {
            const prestamo = data.prestamo;
            const materiales = data.materiales;

            popupModify.innerHTML = `
<h3>Modificar Préstamo: ${operacion_id}</h3>
<form id="formModificar">
<label for="notas">Notas:</label>
<textarea placeholder="Escribe tu comentario aquí" rows="4" cols="50" style="resize: none;" id="notas" name="notas" required>${prestamo.notas}</textarea>

<label for="material">Selecciona Material:</label>
<select id="material" name="material" required>
    <option value="">Selecciona un material</option>
    ${materiales.map(material => {
        return `<option value="${material.material_id}" ${prestamo.material_id === material.material_id ? 'selected' : ''}>
                    ${material.modelo} (${material.tipo_material})
                </option>`;
    }).join('')}
</select>

<button type="button" onclick="guardarDatos(${prestamo.prestamo_id})">Guardar Cambios</button>
</form>
`;
            popupModify.style.display = 'flex';
            document.getElementById("popup").scrollTo(0, 0);
        }
    })
    .catch(error => {
        console.error("Error al obtener los detalles del préstamo:", error);
        popupModify.innerHTML = `<p>Error al obtener los datos. Inténtalo nuevamente.</p>`;
    });
}

// Función para guardar los datos modificados
function guardarDatos(operacion_id) {
    const notas = document.getElementById("notas").value;
    const material_id = document.getElementById("material").value;

    if (!notas || !material_id) {
        alert("Por favor completa todos los campos.");
        return;
    }

    const data = {
        prestamo_id: operacion_id,
        notas: notas,
        material_id: material_id
    };

    console.log("Enviando datos al servidor:", data);

    fetch('/WMS2/LandingPage/phpFiles/config/api_prestamo.php?action=update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.success) {
            alert("Préstamo actualizado exitosamente");
            cerrarPopup(); // Cerrar el popup después de una actualización exitosa
        } else {
            console.error("Error en el servidor:", data.error);
            alert("Error al actualizar el préstamo: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error en la solicitud Fetch:", error);
        alert("Error al procesar la solicitud.");
    });
}
