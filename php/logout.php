<?php
// php/logout.php
session_start(); // Inicia la sesión

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Si se usa sesiones, destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destruir la sesión.
    session_destroy();

    $response['success'] = true;
    $response['message'] = 'Sesión cerrada correctamente.';

    // Si usas JWT, no necesitas sesiones PHP aquí, simplemente el cliente borraría su token.
    // Pero si el token necesita ser invalidado en el servidor (para blacklisting), esa lógica iría aquí.

} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>