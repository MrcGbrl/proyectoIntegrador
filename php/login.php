<?php
// php/login.php
require_once 'config.php'; // Incluye la configuración de la BD

header('Content-Type: application/json'); // Indica que la respuesta será JSON
header('Access-Control-Allow-Origin: *'); // Considera restringir esto en producción
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $cuenta = $input['cuenta'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($cuenta) || empty($password)) {
        $response['message'] = 'Por favor, ingresa tu cuenta y contraseña.';
        echo json_encode($response);
        exit();
    }

    $conn = connectDB();
    // Consulta SQL para verificar las credenciales del alumno
    // **IMPORTANTE**: Usa sentencias preparadas para prevenir inyección SQL
    // Y almacena contraseñas hasheadas (bcrypt) en la BD.
    $stmt = $conn->prepare("SELECT id, password_hash, rol FROM alumnos WHERE cuenta = ?");
    $stmt->bind_param("s", $cuenta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // **IMPORTANTE**: Verificar la contraseña hasheada
        if (password_verify($password, $user['password_hash'])) {
            // Login exitoso
            $response['success'] = true;
            $response['message'] = 'Inicio de sesión exitoso.';
            // Iniciar sesión PHP (si usas sesiones) o generar JWT
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];
            // Para JWT, generar y devolver el token aquí
        } else {
            $response['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $response['message'] = 'Cuenta no encontrada.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>