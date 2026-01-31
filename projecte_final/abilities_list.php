<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    redirect_with_error('not_logged_in');
}

include 'header.php';

$sql = "SELECT a.*, h.nombre AS nombre_heroe
        FROM habilidades a
        JOIN heroes h ON a.heroe_id = h.id
        ORDER BY h.nombre ASC, a.tipo ASC";
$res = mysqli_query($conn, $sql);
if (!$res) {
    redirect_with_error('db_error');
}
?>
<h2>Habilidades</h2>

<?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
    <p><a href="ability_create.php">Crear nueva habilidad</a></p>
<?php endif; ?>

<table>
    <tr>
        <th>Héroe</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Enfriamiento</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nombre_heroe']); ?></td>
            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
            <td><?php echo htmlspecialchars($row['tipo']); ?></td>
            <td><?php echo htmlspecialchars($row['enfriamiento']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['descripcion'])); ?></td>
            <td>
                <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                    <a class="action-edit" href="ability_edit.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a class="action-delete" href="ability_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar habilidad?');">Eliminar</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</main>
</body>
</html>
