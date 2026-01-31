<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // pon aquí tu contraseña de MySQL si tienes
$dbname = 'overwatch_manager';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

function redirect_with_error($code) {
    header("Location: error.php?code=" . urlencode($code));
    exit;
}
?>
