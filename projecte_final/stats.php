<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) redirect_with_error('not_logged_in');

include 'header.php';

$sql1 = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
$sql2 = "SELECT COUNT(*) AS total_heroes FROM heroes";
$sql3 = "SELECT COUNT(*) AS total_valoraciones FROM valoraciones_heroes";
$sql4 = "SELECT h.nombre, COALESCE(AVG(vh.puntuacion),0) AS media_puntuacion, COUNT(vh.id) AS num_valoraciones
         FROM heroes h
         LEFT JOIN valoraciones_heroes vh ON h.id = vh.heroe_id
         GROUP BY h.id
         HAVING num_valoraciones > 0
         ORDER BY media_puntuacion DESC
         LIMIT 5";

$r1 = mysqli_query($conn, $sql1);
$r2 = mysqli_query($conn, $sql2);
$r3 = mysqli_query($conn, $sql3);
$r4 = mysqli_query($conn, $sql4);

$tot_usuarios   = $r1 ? mysqli_fetch_assoc($r1)['total_usuarios'] : 0;
$tot_heroes     = $r2 ? mysqli_fetch_assoc($r2)['total_heroes'] : 0;
$tot_valoraciones = $r3 ? mysqli_fetch_assoc($r3)['total_valoraciones'] : 0;
?>
<h2>Estadísticas</h2>
<ul>
    <li>Usuarios registrados: <?php echo $tot_usuarios; ?></li>
    <li>Héroes registrados: <?php echo $tot_heroes; ?></li>
    <li>Valoraciones totales: <?php echo $tot_valoraciones; ?></li>
</ul>

<h3>Top 5 héroes mejor valorados</h3>
<table>
    <tr>
        <th>Héroe</th>
        <th>Valoración media</th>
        <th>Nº valoraciones</th>
    </tr>
    <?php if ($r4 && mysqli_num_rows($r4) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($r4)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo number_format($row['media_puntuacion'], 2); ?></td>
                <td><?php echo $row['num_valoraciones']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="3">Sin valoraciones aún.</td></tr>
    <?php endif; ?>
</table>
</main>
</body>
</html>
