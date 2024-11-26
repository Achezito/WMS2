document.addEventListener('DOMContentLoaded', function() {
    const finalizacionModal = document.getElementById("finalizacionModal");
    const span = document.getElementsByClassName("close")[0];
    const finalizacionMantenimientoId = document.getElementById("finalizacionMantenimientoId");

    // Abrir modal de finalización
    document.querySelectorAll('.finalizar-mantenimiento-btn').forEach(button => {
        button.addEventListener('click', function() {
            const mantenimientoId = this.getAttribute('data-mantenimiento-id');
            finalizacionMantenimientoId.value = mantenimientoId;
            finalizacionModal.style.display = "block";
        });
    });

    // Cerrar modal al hacer clic en "X"
    span.addEventListener('click', function() {
        finalizacionModal.style.display = "none";
    });

    // Cerrar modal al hacer clic fuera del modal
    window.addEventListener('click', function(event) {
        if (event.target == finalizacionModal) {
            finalizacionModal.style.display = "none";
        }
    });
});
