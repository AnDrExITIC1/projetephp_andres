<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    redirect_with_error('not_logged_in');
}
if ($_SESSION['usuario']['rol'] !== 'admin') {
    redirect_with_error('no_permission');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect_with_error('invalid_id');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $rol = $_POST['rol'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $imagen_actual = $_POST['imagen_actual'] ?? '';

    if ($nombre === '' || $rol === '') {
        redirect_with_error('empty_fields');
    }

    if (!in_array($rol, ['Tanque','Daño','Apoyo'])) {
        redirect_with_error('db_error');
    }

    $imagen_nombre = $imagen_actual;
    if (!empty($_FILES['imagen']['name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $imagen_nombre = time() . '_' . basename($_FILES['imagen']['name']);
        $destino = $upload_dir . $imagen_nombre;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            redirect_with_error('upload_error');
        }
    }

    $nombre_esc = mysqli_real_escape_string($conn, $nombre);
    $rol_esc = mysqli_real_escape_string($conn, $rol);
    $desc_esc = mysqli_real_escape_string($conn, $descripcion);
    $img_esc  = $imagen_nombre ? "'" . mysqli_real_escape_string($conn, $imagen_nombre) . "'" : "NULL";

    $sql = "UPDATE heroes
            SET nombre='$nombre_esc', rol='$rol_esc', descripcion='$desc_esc', imagen=$img_esc
            WHERE id=$id";
    if (!mysqli_query($conn, $sql)) {
        redirect_with_error('db_error');
    }

    header('Location: heroes_list.php');
    exit;
}

$sql = "SELECT * FROM heroes WHERE id=$id";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    redirect_with_error('invalid_id');
}
$heroe = mysqli_fetch_assoc($res);

include 'header.php';
?>
<h2>Editar héroe</h2>
<form method="post" action="hero_edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
    <label>Nombre:<br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($heroe['nombre']); ?>" required>
    </label><br><br>
    <label>Rol:<br>
        <select name="rol" required>
            <option value="Tanque" <?php if($heroe['rol']==='Tanque') echo 'selected'; ?>>Tanque</option>
            <option value="Daño" <?php if($heroe['rol']==='Daño') echo 'selected'; ?>>Daño</option>
            <option value="Apoyo" <?php if($heroe['rol']==='Apoyo') echo 'selected'; ?>>Apoyo</option>
        </select>
    </label><br><br>
    <label>Descripción:<br>
        <textarea name="descripcion" rows="4" cols="40"><?php echo htmlspecialchars($heroe['descripcion']); ?></textarea>
    </label><br><br>
    <?php if ($heroe['imagen']): ?>
        <p>Imagen actual:<br>
            <img src="uploads/<?php echo htmlspecialchars($heroe['imagen']); ?>" width="100">
        </p>
    <?php endif; ?>
    <label>Nueva imagen (opcional):<br>
        <input type="file" name="imagen" accept="image/*">
    </label><br><br>
    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($heroe['imagen']); ?>">
    <button type="submit">Guardar cambios</button>
</form>
</main>
</body>
</html>
