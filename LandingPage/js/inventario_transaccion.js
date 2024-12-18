document.addEventListener('DOMContentLoaded', function() {
    const agregarMaterialBtn = document.getElementById("agregarMaterial");
    const materialesTableBody = document.getElementById("materialesTable").querySelector("tbody");
    const tipoTransaccionSelect = document.getElementById("tipo_transaccion");
    const entradaMaterialesDiv = document.getElementById("entradaMateriales");
    const salidaMaterialesDiv = document.getElementById("salidaMateriales");
    const agregarMaterialSalidaBtn = document.getElementById("agregarMaterialSalida");
    const agregarMaterialesTableBody = document.getElementById("agregarMaterialesTable").querySelector("tbody");
    const materialesDisponiblesSelect = document.getElementById("materialesDisponibles");

    // Cambiar el contexto al seleccionar el tipo de transacción
    tipoTransaccionSelect.addEventListener('change', function() {
        if (this.value === 'entrada') {
            entradaMaterialesDiv.style.display = 'block';
            salidaMaterialesDiv.style.display = 'none';
        } else if (this.value === 'salida') {
            entradaMaterialesDiv.style.display = 'none';
            salidaMaterialesDiv.style.display = 'block';
        }
    });

    // Mostrar la funcionalidad completa de "Entrada" al cargar la página
    if (tipoTransaccionSelect.value === 'entrada') {
        entradaMaterialesDiv.style.display = 'block';
        salidaMaterialesDiv.style.display = 'none';
    }

    // Agregar materiales al inventario (para entrada)
    agregarMaterialBtn.addEventListener('click', function() {
        event.preventDefault();  // Esto evita que el evento de click haga algo inesperado.
        const modeloMaterial = document.getElementById('modelo_material').value;
        const tipoMaterial = document.getElementById('tipo_material').value;
        const cantidadMaterial = document.getElementById('cantidad_material').value;

        for (let i = 0; i < cantidadMaterial; i++) {
            const row = document.createElement('tr');

            const serieCell = document.createElement('td');
            const serieInput = document.createElement('input');
            serieInput.type = 'text';
            serieInput.name = 'series[]';
            serieInput.required = true;
            serieCell.appendChild(serieInput);

            const modeloCell = document.createElement('td');
            const modeloInput = document.createElement('input');
            modeloInput.type = 'text';
            modeloInput.name = 'modelos[]';
            modeloInput.value = modeloMaterial;
            modeloInput.required = true;
            modeloCell.appendChild(modeloInput);

            const tipoCell = document.createElement('td');
            const tipoInput = document.createElement('input');
            tipoInput.type = 'text';
            tipoInput.name = 'tipos[]';
            tipoInput.value = tipoMaterial;
            tipoInput.required = true;
            tipoCell.appendChild(tipoInput);

            const actionCell = document.createElement('td');
            const eliminarBtn = document.createElement('button');
            eliminarBtn.type = 'button';
            eliminarBtn.classList.add('eliminarMaterial');
            eliminarBtn.textContent = 'Eliminar';
            actionCell.appendChild(eliminarBtn);

            row.appendChild(serieCell);
            row.appendChild(modeloCell);
            row.appendChild(tipoCell);
            row.appendChild(actionCell);

            materialesTableBody.appendChild(row);
        }

        // Limpiar campos después de agregar materiales
        document.getElementById('modelo_material').value = '';
        document.getElementById('cantidad_material').value = '';
    });

    // Eliminar materiales del inventario
    materialesTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('eliminarMaterial')) {
            event.target.closest('tr').remove();
        }
    });

    // Agregar materiales a la lista de salida
    agregarMaterialSalidaBtn.addEventListener('click', function() {
        const materialId = materialesDisponiblesSelect.value;
        const materialText = materialesDisponiblesSelect.options[materialesDisponiblesSelect.selectedIndex].text;

        // Verificar si el material ya está en la lista de salida
        const existingRows = agregarMaterialesTableBody.querySelectorAll('tr');
        for (let row of existingRows) {
            if (row.querySelector('input[name="materiales[]"]').value === materialId) {
                alert('Este material ya está en la lista.');
                return;
            }
        }

        const row = document.createElement('tr');

        const serieCell = document.createElement('td');
        serieCell.textContent = materialText.split(' - ')[0];

        const modeloCell = document.createElement('td');
        modeloCell.textContent = materialText.split(' - ')[1];

        const actionCell = document.createElement('td');
        const eliminarBtn = document.createElement('button');
        eliminarBtn.type = 'button';
        eliminarBtn.classList.add('eliminarMaterial');
        eliminarBtn.textContent = 'Eliminar';
        actionCell.appendChild(eliminarBtn);

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'materiales[]';
        hiddenInput.value = materialId;

        row.appendChild(serieCell);
        row.appendChild(modeloCell);
        row.appendChild(actionCell);
        row.appendChild(hiddenInput);

        agregarMaterialesTableBody.appendChild(row);
    });

    // Eliminar materiales de la lista de salida
    agregarMaterialesTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('eliminarMaterial')) {
            event.target.closest('tr').remove();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');  // Asegúrate de tener un id para tu formulario
    const mensajeDiv = document.getElementById('messageContainer'); // Div donde se mostrará el mensaje
    console.log(mensajeDiv); // Verifica si el elemento se selecciona correctamente

    form.addEventListener('submit', function(event) {
        event.preventDefault();  // Prevenir que se recargue la página

        const formData = new FormData(form); // Obtiene todos los datos del formulario

        // Usamos fetch para enviar los datos de forma asincrónica
        fetch('../formularios/transacciones.php', {  // Aquí va la URL del script que procesará la solicitud
            method: 'POST',
            body: formData,
        })
        .then(response => response.json()) // Asumiendo que la respuesta será JSON
        .then(data => {
            // Muestra el mensaje de éxito o error
            if (data.success) {
                mensajeDiv.innerHTML = "Transacción registrada con éxito! Nota: puedes ver los materiales en la seccion principal.";
                mensajeDiv.style.color = 'green';
            } else {
                mensajeDiv.innerHTML = "Hubo un error al registrar la transacción.";
                mensajeDiv.style.color = 'red';
            }
            mensajeDiv.style.display = 'block';  // Hacer visible el mensaje
        })
        .catch(error => {
            console.error('Error:', error);
            mensajeDiv.innerHTML = "Hubo un problema con la solicitud.";
            mensajeDiv.style.color = 'red';
            mensajeDiv.style.display = 'block';  // Hacer visible el mensaje
        });
    });
});
