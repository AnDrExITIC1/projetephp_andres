<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    redirect_with_error('not_logged_in');
}

include 'header.php';

$rol = $_SESSION['usuario']['rol'];
?>
<h2>Panel principal</h2>

<?php if ($rol === 'admin'): ?>
    <h3>Opciones de administrador</h3>
    <ul>
        <li><a href="heroes_list.php">Gestionar héroes (CRUD)</a></li>
        <li><a href="abilities_list.php">Gestionar habilidades (CRUD)</a></li>
        <li><a href="stats.php">Ver estadísticas</a></li>
    </ul>
<?php else: ?>
    <h3>Opciones de jugador</h3>
    <ul>
        <li><a href="heroes_list.php">Ver héroes y filtrar</a></li>
        <li><a href="ratings.php">Valorar héroes</a></li>
        <li><a href="stats.php">Ver héroes mejor valorados</a></li>
    </ul>
<?php endif; ?>

</main>
</body>
</html>
