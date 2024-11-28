        function cambiarHistorial(index) {
            // Ocultar todos los historiales
            const historiales = document.querySelectorAll('.historial');
            historiales.forEach(historial => historial.style.display = 'none');

            // Mostrar el historial seleccionado
            document.getElementById('historial-' + index).style.display = 'block';

            // Establecer el tipo de operación según el índice
            let tipoOperacion = '';
            switch (index) {
                case 0:
                    tipoOperacion = 'Operación';
                    break;
                case 1:
                    tipoOperacion = 'Préstamo';
                    break;
                case 2:
                    tipoOperacion = 'Mantenimiento';
                    break;
                case 3:
                    tipoOperacion = 'Transacción';
                    break;
            }

            // Asignar tipo de operación a los botones del historial correspondiente
            const buttons = document.querySelectorAll('#historial-' + index + ' .modal-button');
            buttons.forEach(button => {
                if (index == 0) {
                    // No asignamos tipo de operación ya que se extrae del HTML
                } else {
                    button.dataset.tipoOperacion = tipoOperacion;
                }
            });
        }

        function abrirModal(event) {
            const button = event.currentTarget;
            let tipoOperacion = button.dataset.tipoOperacion;
            const operacionId = button.dataset.operacionId;

            if (!tipoOperacion) {
                // Si no hay tipoOperacion en el dataset, obtener del texto del botón en la tabla del historial de operaciones
                tipoOperacion = button.closest('tr').querySelector('td:first-child').innerText.trim();
            }

            const modalOverlay = document.getElementById('modal-overlay');
            const modalBody = document.getElementById('modal-body');

            modalBody.innerHTML = `<p>Cargando detalles para la operación...</p>`;
            obtenerMateriales(tipoOperacion, operacionId);

            modalOverlay.style.display = 'flex';
        }

        function obtenerMateriales(tipoOperacion, operacionId) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `../../../phpFiles/Models/historiales.php?tipo_operacion=${tipoOperacion}&operacion_id=${operacionId}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('modal-body').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('modal-body').innerHTML = `<p>Error al cargar los datos.</p>`;
                }
            };
            xhr.onerror = function() {
                document.getElementById('modal-body').innerHTML = `<p>Error de red al intentar cargar los datos.</p>`;
            };
            xhr.send();
        }

        function cerrarModal() {
            // Oculta el modal y limpia el contenido del modal
            const modalOverlay = document.getElementById('modal-overlay');
            modalOverlay.style.display = 'none';
            document.getElementById('modal-body').innerHTML = ''; // Limpiar el contenido del modal
        }

        // Al cargar la página, mostrar solo el historial 0 (Actividad Personal)
        window.onload = function() {
            cambiarHistorial(0);
            document.getElementById('close-modal').onclick = cerrarModal;
        };
