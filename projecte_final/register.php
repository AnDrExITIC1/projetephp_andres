<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pass1    = $_POST['contrasena'] ?? '';
    $pass2    = $_POST['contrasena2'] ?? '';

    if ($nombre === '' || $email === '' || $pass1 === '' || $pass2 === '') {
        redirect_with_error('empty_fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_error('invalid_email');
    }

    if ($pass1 !== $pass2) {
        redirect_with_error('password_mismatch');
    }

    $email_esc = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT id FROM usuarios WHERE email='$email_esc'";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        redirect_with_error('user_exists');
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);

    $nombre_esc = mysqli_real_escape_string($conn, $nombre);
    $hash_esc   = mysqli_real_escape_string($conn, $hash);

    // rol jugador (id=2)
    $sql = "INSERT INTO usuarios (nombre, email, contrasena, rol_id)
            VALUES ('$nombre_esc', '$email_esc', '$hash_esc', 2)";
    if (!mysqli_query($conn, $sql)) {
        redirect_with_error('db_error');
    }

    header('Location: login.php');
    exit;
}

include 'header.php';
?>
<h2>Registro</h2>
<form method="post" action="register.php">
    <label>Nombre:<br>
        <input type="text" name="nombre" required>
    </label><br><br>
    <label>Email:<br>
        <input type="email" name="email" required>
    </label><br><br>
    <label>Contraseña:<br>
        <input type="password" name="contrasena" required>
    </label><br><br>
    <label>Repite la contraseña:<br>
        <input type="password" name="contrasena2" required>
    </label><br><br>
    <button type="submit">Registrarse</button>
</form>
</main>
</body>
</html>
