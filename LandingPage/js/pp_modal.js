document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("materialesModal");
    const rechazoModal = document.getElementById("rechazoModal");
    const span = document.getElementsByClassName("close");
    const materialesContent = document.getElementById("materialesContent");
    const rechazoPrestamoId = document.getElementById("rechazoPrestamoId");

    // Abrir modal de materiales y cargar materiales
    document.querySelectorAll('.ver-materiales-btn').forEach(button => {
        button.addEventListener('click', function() {
            const prestamoId = this.getAttribute('data-prestamo-id');
            fetchMateriales(prestamoId);
            modal.style.display = "block";
        });
    });

    // Abrir modal de rechazo
    document.querySelectorAll('.rechazar-prestamo-btn').forEach(button => {
        button.addEventListener('click', function() {
            const prestamoId = this.getAttribute('data-prestamo-id');
            rechazoPrestamoId.value = prestamoId;
            rechazoModal.style.display = "block";
        });
    });

    // Cerrar modales
    Array.from(span).forEach(element => {
        element.onclick = function() {
            modal.style.display = "none";
            rechazoModal.style.display = "none";
        }
    });

    window.onclick = function(event) {
        if (event.target == modal || event.target == rechazoModal) {
            modal.style.display = "none";
            rechazoModal.style.display = "none";
        }
    }

    function fetchMateriales(prestamoId) {
        // Hacer la solicitud AJAX a personal_prestamos.php para obtener los materiales
        fetch(`personal_prestamos.php?prestamo_id=${prestamoId}`)
            .then(response => response.text())
            .then(data => {
                materialesContent.innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }
});
