document.addEventListener('DOMContentLoaded', () => {
    const recoveryForm = document.getElementById('recoveryForm');

    if (recoveryForm) {
        recoveryForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const noControl = document.getElementById('noControl').value;

            try {
                const response = await fetch('php/recuperar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ noControl }),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Se ha enviado la nueva contraseña a tu correo institucional.');
                    // Opcionalmente, redirigir al login
                    window.location.href = 'login_alumnos.html';
                } else {
                    alert(data.message || 'Error al recuperar contraseña. Verifica tu número de control.');
                }
            } catch (error) {
                console.error('Error durante la recuperación de contraseña:', error);
                alert('Ocurrió un error en el servidor. Inténtalo de nuevo más tarde.');
            }
        });
    }
});