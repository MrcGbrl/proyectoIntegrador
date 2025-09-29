<?php
// php/recuperar.php
require_once 'config.php'; // Incluye la configuración de la BD

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $noControl = $input['noControl'] ?? '';

    if (empty($noControl)) {
        $response['message'] = 'Por favor, ingresa tu número de control.';
        echo json_encode($response);
        exit();
    }

    $conn = connectDB();
    // 1. Verificar si el número de control existe y obtener el correo institucional
    $stmt = $conn->prepare("SELECT id, correo_institucional FROM alumnos WHERE no_control = ?");
    $stmt->bind_param("s", $noControl);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $correoInstitucional = $user['correo_institucional'];

        // 2. Generar una nueva contraseña aleatoria
        $newPassword = substr(md5(uniqid(rand(), true)), 0, 10); // Contraseña simple, mejorar en producción
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hashear la nueva contraseña

        // 3. Actualizar la contraseña en la base de datos
        $updateStmt = $conn->prepare("UPDATE alumnos SET password_hash = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $user['id']);

        if ($updateStmt->execute()) {
            // 4. Enviar la nueva contraseña al correo institucional
            // **IMPORTANTE**: Configurar un servidor de correo (SMTP) para enviar correos.
            // Esto es solo un placeholder:
            $subject = "Recuperación de Contraseña - ITSZ";
            $message = "Hola,\n\nTu nueva contraseña para el sistema ITSZ es: " . $newPassword . "\n\nPor favor, cámbiala después de iniciar sesión.\n\nSaludos.";
            $headers = "From: no-reply@itsz.edu.mx" . "\r\n" .
                       "Reply-To: no-reply@itsz.edu.mx" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            // Descomentar y configurar el envío de correo real
            // if (mail($correoInstitucional, $subject, $message, $headers)) {
                $response['success'] = true;
                $response['message'] = 'Se ha enviado la nueva contraseña a tu correo institucional.';
            // } else {
            //     $response['message'] = 'Error al enviar el correo. Por favor, contacta a soporte.';
            // }
        } else {
            $response['message'] = 'Error al actualizar la contraseña en la base de datos.';
        }
        $updateStmt->close();
    } else {
        $response['message'] = 'Número de control no encontrado.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>