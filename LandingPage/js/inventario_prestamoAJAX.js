document.addEventListener('DOMContentLoaded', function () {
    $('#material').select2();
    document.getElementById('solicitudForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Evitar el envío tradicional del formulario

        var formData = new FormData(this); // Obtener los datos del formulario

        // Mostrar en consola los datos del formulario para ver el contenido
        for (var [key, value] of formData.entries()) {
            console.log(key + ': ' + value); // Verifica qué datos se están enviando
        }

        const errorDiv = document.getElementById("error");

        fetch('../../phpFiles/Models/inventario_prestamo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.style.color = 'green';
                messageDiv.textContent = data.message; // Mostrar mensaje de éxito
            } else {
                messageDiv.style.color = 'red';
                messageDiv.textContent = data.message; // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error);
            var messageDiv = document.getElementById('message');
            messageDiv.style.color = 'red';
            messageDiv.textContent = 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo.';
        });
    });
});
