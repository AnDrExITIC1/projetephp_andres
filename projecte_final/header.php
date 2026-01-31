<?php
$current = basename($_SERVER['PHP_SELF']); // Detecta la página actual
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Héroes Overwatch</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div>
        <h1>Gestor de Héroes Overwatch</h1>
        <?php if (isset($_SESSION['usuario'])): ?>
            <p>
                Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></strong>
                (<?php echo htmlspecialchars($_SESSION['usuario']['rol']); ?>)
            </p>
        <?php endif; ?>
    </div>
    <nav>
        <?php if (!isset($_SESSION['usuario'])): ?>
            <a href="index.html">Inicio</a>
            <a href="login.php">Login</a>
            <a href="register.php">Registro</a>
        <?php else: ?>
            <a href="access.php">Inicio</a>
            <a href="heroes_list.php">Héroes</a>
            <a href="abilities_list.php">Habilidades</a>
            <a href="stats.php">Estadísticas</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>
<main>
