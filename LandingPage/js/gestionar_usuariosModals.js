document.addEventListener('DOMContentLoaded', () => {
    const tipoCuentaSelect = document.getElementById("tipoCuenta");
    const personalFields = document.getElementById("personalFields");
    const usuarioFields = document.getElementById("usuarioFields");

    const toggleInputs = (fields, isDisabled) => {
        const inputs = fields.querySelectorAll("input, select");
        inputs.forEach(input => {
            input.disabled = isDisabled;
        });
    };

    const toggleAccountTypeFields = () => {
        const tipoCuenta = tipoCuentaSelect.value;
        if (tipoCuenta === "personal") {
            personalFields.style.display = "block";
            usuarioFields.style.display = "none";
            toggleInputs(personalFields, false); // Habilitar inputs de Personal
            toggleInputs(usuarioFields, true);  // Deshabilitar inputs de Usuario
        } else if (tipoCuenta === "usuario") {
            personalFields.style.display = "none";
            usuarioFields.style.display = "block";
            toggleInputs(personalFields, true);  // Deshabilitar inputs de Personal
            toggleInputs(usuarioFields, false); // Habilitar inputs de Usuario
        }
    };

    // Cambiar campos según el tipo de cuenta seleccionado
    tipoCuentaSelect.addEventListener('change', toggleAccountTypeFields);

    // Inicializar los campos al cargar la página
    toggleAccountTypeFields();
});
   // Obtener elementos del DOM


   document.addEventListener('DOMContentLoaded', (event) => {
    const addUserBtn = document.getElementById("addUserBtn");
    const addUserModal = document.getElementById("addUserModal");
    const closeAddUserModal = document.getElementById("closeAddUserModal");
  

    if (addUserBtn && addUserModal && closeAddUserModal) {
        addUserBtn.addEventListener('click', function() {
            addUserModal.style.display = "flex";
            toggleAccountTypeFields(); // Ajustar campos según el tipo de cuenta
        });

        closeAddUserModal.addEventListener('click', function() {
            addUserModal.style.display = "none";
        });
    } else {
        console.error('Uno o más elementos no existen en el DOM');
    }


  
});

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("actionModal");
    const modalTitle = document.getElementById("modalTitle");
    const personalFields = document.getElementById("personalFields2");
    const usuarioFields = document.getElementById("usuarioFields2");
    const closeModal = document.getElementById("closeActionModal");

    // Función para desactivar todos los inputs
    function toggleInputs(parent, disable) {
        const inputs = parent.querySelectorAll("input, select, textarea");
        inputs.forEach(input => {
            input.disabled = disable;
        });
    }

    // Escuchar clicks en los botones de acciones
    document.querySelectorAll(".btn-actions").forEach((button) => {
        button.addEventListener("click", function () {
            // Obtener los datos del botón
            const row = this.closest(".user-row");
            const tipoCuenta = row.getAttribute("data-cuenta");
            const nombreUsuario = row.getAttribute("data-usuario");

            // Ajustar el título del modal
            modalTitle.textContent = `Acciones para ${nombreUsuario}`;

            // Mostrar/ocultar campos específicos y desactivar/activar inputs
            if (tipoCuenta === "personal") {
                personalFields.style.display = "block";
                usuarioFields.style.display = "none";

                toggleInputs(personalFields, false); // Activar inputs para "Personal"
                toggleInputs(usuarioFields, true);  // Desactivar inputs para "Usuario"
            } else if (tipoCuenta === "usuario") {
                personalFields.style.display = "none";
                usuarioFields.style.display = "flex";

                toggleInputs(personalFields, true); // Desactivar inputs para "Personal"
                toggleInputs(usuarioFields, false); // Activar inputs para "Usuario"
            }

            // Mostrar el modal
            modal.style.display = "flex";
        });
    });

    // Cerrar el modal
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });
});
