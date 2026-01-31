<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    redirect_with_error('not_logged_in');
}

include 'header.php';

$busqueda = trim($_GET['busqueda'] ?? '');
$filtro_rol = $_GET['rol'] ?? '';
$orden = $_GET['orden'] ?? 'rol';

$where = [];
if ($busqueda !== '') {
    $busqueda_esc = mysqli_real_escape_string($conn, $busqueda);
    $where[] = "h.nombre LIKE '%$busqueda_esc%'";
}
if ($filtro_rol !== '' && in_array($filtro_rol, ['Tanque','Daño','Apoyo'])) {
    $rol_esc = mysqli_real_escape_string($conn, $filtro_rol);
    $where[] = "h.rol='$rol_esc'";
}

$where_sql = '';
if (count($where) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
}

switch ($orden) {
    case 'nombre':
        $orden_sql = 'ORDER BY h.nombre ASC';
        break;
    case 'creado':
        $orden_sql = 'ORDER BY h.creado_en DESC';
        break;
    case 'puntuacion':
        $orden_sql = 'ORDER BY media_puntuacion DESC, h.nombre ASC';
        break;
    default:
        $orden_sql = 'ORDER BY h.rol ASC, h.nombre ASC';
}

$sql = "SELECT h.*,
        COALESCE(AVG(vh.puntuacion),0) AS media_puntuacion,
        COUNT(vh.id) AS num_valoraciones
        FROM heroes h
        LEFT JOIN valoraciones_heroes vh ON h.id = vh.heroe_id
        $where_sql
        GROUP BY h.id
        $orden_sql";

$res = mysqli_query($conn, $sql);
if (!$res) {
    redirect_with_error('db_error');
}
?>
<h2>Héroes</h2>

<form method="get" action="heroes_list.php">
    <label>Buscar por nombre:
        <input type="text" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>">
    </label>
    <label>Rol:
        <select name="rol">
            <option value="">Todos</option>
            <option value="Tanque" <?php if($filtro_rol==='Tanque') echo 'selected'; ?>>Tanque</option>
            <option value="Daño" <?php if($filtro_rol==='Daño') echo 'selected'; ?>>Daño</option>
            <option value="Apoyo" <?php if($filtro_rol==='Apoyo') echo 'selected'; ?>>Apoyo</option>
        </select>
    </label>
    <label>Ordenar por:
        <select name="orden">
            <option value="rol" <?php if($orden==='rol') echo 'selected'; ?>>Rol</option>
            <option value="nombre" <?php if($orden==='nombre') echo 'selected'; ?>>Nombre</option>
            <option value="creado" <?php if($orden==='creado') echo 'selected'; ?>>Más recientes</option>
            <option value="puntuacion" <?php if($orden==='puntuacion') echo 'selected'; ?>>Mejor valorados</option>
        </select>
    </label>
    <button type="submit">Aplicar</button>
</form>

<?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
    <p><a href="hero_create.php">Crear nuevo héroe</a></p>
<?php endif; ?>

<table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Descripción</th>
        <th>Valoración media</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td>
                <?php if ($row['imagen']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="" width="80">
                <?php else: ?>
                    Sin imagen
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
            <td><?php echo htmlspecialchars($row['rol']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['descripcion'])); ?></td>
            <td><?php echo number_format($row['media_puntuacion'], 2); ?> (<?php echo $row['num_valoraciones']; ?>)</td>
            <td>
                <a class="action-view" href="ratings.php?heroe_id=<?php echo $row['id']; ?>">Valorar</a>
                <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                    <a class="action-edit" href="hero_edit.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a class="action-delete" href="hero_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar héroe?');">Eliminar</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</main>
</body>
</html>
