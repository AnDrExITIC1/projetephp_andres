<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['contrasena'] ?? '';

    if ($email === '' || $pass === '') {
        redirect_with_error('empty_fields');
    }

    $email_esc = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT u.id, u.nombre, u.email, u.contrasena, r.nombre AS rol
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            WHERE u.email='$email_esc'
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $usuario = mysqli_fetch_assoc($res);
        if (password_verify($pass, $usuario['contrasena'])) {
            $_SESSION['usuario'] = [
                'id'    => $usuario['id'],
                'nombre'=> $usuario['nombre'],
                'email' => $usuario['email'],
                'rol'   => $usuario['rol']
            ];
            header('Location: access.php');
            exit;
        }
    }
    redirect_with_error('login_failed');
}

include 'header.php';
?>
<h2>Login</h2>
<form method="post" action="login.php">
    <label>Email:<br>
        <input type="email" name="email" required>
    </label><br><br>
    <label>Contraseña:<br>
        <input type="password" name="contrasena" required>
    </label><br><br>
    <button type="submit">Entrar</button>
</form>
</main>
</body>
</html>
