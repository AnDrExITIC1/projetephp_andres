<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) redirect_with_error('not_logged_in');
if ($_SESSION['usuario']['rol'] !== 'admin') redirect_with_error('no_permission');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect_with_error('invalid_id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heroe_id = (int)($_POST['heroe_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $enfriamiento = $_POST['enfriamiento'] !== '' ? (float)$_POST['enfriamiento'] : null;

    if ($heroe_id <= 0 || $nombre === '' || $tipo === '') {
        redirect_with_error('empty_fields');
    }

    if (!in_array($tipo, ['Principal','Secundaria','Habilidad','Definitiva'])) {
        redirect_with_error('db_error');
    }

    $nombre_esc = mysqli_real_escape_string($conn, $nombre);
    $tipo_esc = mysqli_real_escape_string($conn, $tipo);
    $desc_esc = mysqli_real_escape_string($conn, $descripcion);
    $enf_sql = is_null($enfriamiento) ? "NULL" : (float)$enfriamiento;

    $sql = "UPDATE habilidades
            SET heroe_id=$heroe_id, nombre='$nombre_esc', tipo='$tipo_esc', descripcion='$desc_esc', enfriamiento=$enf_sql
            WHERE id=$id";
    if (!mysqli_query($conn, $sql)) {
        redirect_with_error('db_error');
    }

    header('Location: abilities_list.php');
    exit;
}

$hab_res = mysqli_query($conn, "SELECT * FROM habilidades WHERE id=$id");
if (!$hab_res || mysqli_num_rows($hab_res) === 0) redirect_with_error('invalid_id');
$habilidad = mysqli_fetch_assoc($hab_res);

$heroes_res = mysqli_query($conn, "SELECT id, nombre FROM heroes ORDER BY nombre ASC");
if (!$heroes_res) redirect_with_error('db_error');

include 'header.php';
?>
<h2>Editar habilidad</h2>
<form method="post" action="ability_edit.php?id=<?php echo $id; ?>">
    <label>Héroe:<br>
        <select name="heroe_id" required>
            <?php while ($h = mysqli_fetch_assoc($heroes_res)): ?>
                <option value="<?php echo $h['id']; ?>" <?php if($h['id']==$habilidad['heroe_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($h['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </label><br><br>
    <label>Nombre:<br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($habilidad['nombre']); ?>" required>
    </label><br><br>
    <label>Tipo:<br>
        <select name="tipo" required>
            <option value="Principal"   <?php if($habilidad['tipo']==='Principal') echo 'selected'; ?>>Principal</option>
            <option value="Secundaria" <?php if($habilidad['tipo']==='Secundaria') echo 'selected'; ?>>Secundaria</option>
            <option value="Habilidad"   <?php if($habilidad['tipo']==='Habilidad') echo 'selected'; ?>>Habilidad</option>
            <option value="Definitiva"  <?php if($habilidad['tipo']==='Definitiva') echo 'selected'; ?>>Definitiva</option>
        </select>
    </label><br><br>
    <label>Enfriamiento:<br>
        <input type="number" step="0.01" name="enfriamiento" value="<?php echo htmlspecialchars($habilidad['enfriamiento']); ?>">
    </label><br><br>
    <label>Descripción:<br>
        <textarea name="descripcion" rows="4" cols="40"><?php echo htmlspecialchars($habilidad['descripcion']); ?></textarea>
    </label><br><br>
    <button type="submit">Guardar cambios</button>
</form>
</main>
</body>
</html>
