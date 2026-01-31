<?php
require 'config.php';
include 'header.php';

$code = isset($_GET['code']) ? $_GET['code'] : 'unknown';

$messages = [
    'empty_fields' => 'Hay campos obligatorios vacíos.',
    'invalid_email' => 'El email no tiene un formato válido.',
    'password_mismatch' => 'Las contraseñas no coinciden.',
    'user_exists' => 'Ya existe un usuario con ese email.',
    'login_failed' => 'Email o contraseña incorrectos.',
    'not_logged_in' => 'Debes iniciar sesión para acceder a esta página.',
    'no_permission' => 'No tienes permisos para acceder a esta sección.',
    'invalid_id' => 'ID no válido.',
    'db_error' => 'Error en la base de datos.',
    'upload_error' => 'Error al subir la imagen.',
    'unknown' => 'Ha ocurrido un error desconocido.'
];

$message = isset($messages[$code]) ? $messages[$code] : $messages['unknown'];
?>
<h2>Error</h2>
<p><?php echo htmlspecialchars($message); ?></p>
<a href="index.html">Volver al inicio</a>
</main>
</body>
</html>
