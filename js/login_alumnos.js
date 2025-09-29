document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Evita el envío tradicional del formulario

            const cuentaInput = document.getElementById('cuenta');
            const passwordInput = document.getElementById('password');

            const cuenta = cuentaInput.value;
            const password = passwordInput.value;

            // --- Lógica de Autenticación Genérica (SOLO PARA DESARROLLO) ---
            const USUARIO_GENERICO = 'demo';
            const CONTRASENA_GENERICA = 'password';

            if (cuenta === USUARIO_GENERICO && password === CONTRASENA_GENERICA) {
                alert('¡Inicio de sesión genérico exitoso! Redirigiendo...');
                // Simula un pequeño retraso antes de redirigir
                setTimeout(() => {
                    window.location.href = 'menu_alumnos.html';
                }, 500); // Redirige después de 0.5 segundos
            } else {
                alert('Credenciales genéricas incorrectas. Intenta con usuario: "demo", contraseña: "password"');
                // Opcional: limpiar campos o enfocar input
                passwordInput.value = ''; // Limpia la contraseña para otro intento
                cuentaInput.focus(); // Vuelve a enfocar el campo de usuario
            }
            // --- FIN Lógica de Autenticación Genérica ---

            // ** Cuando quieras integrar el backend real con PHP, descomenta el siguiente bloque **
            /*
            try {
                const response = await fetch('php/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cuenta, password }),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    window.location.href = 'menu_alumnos.html';
                } else {
                    alert(data.message || 'Error de autenticación. Verifica tus credenciales.');
                }
            } catch (error) {
                console.error('Error durante el inicio de sesión:', error);
                alert('Ocurrió un error en el servidor. Inténtalo de nuevo más tarde.');
            }
            */
            // ** Fin del bloque de backend real **
        });
    }
});