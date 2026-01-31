<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    redirect_with_error('not_logged_in');
}
if ($_SESSION['usuario']['rol'] !== 'admin') {
    redirect_with_error('no_permission');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $rol = $_POST['rol'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre === '' || $rol === '') {
        redirect_with_error('empty_fields');
    }

    if (!in_array($rol, ['Tanque','Daño','Apoyo'])) {
        redirect_with_error('db_error');
    }

    $imagen_nombre = null;
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

    $sql = "INSERT INTO heroes (nombre, rol, descripcion, imagen)
            VALUES ('$nombre_esc', '$rol_esc', '$desc_esc', $img_esc)";
    if (!mysqli_query($conn, $sql)) {
        redirect_with_error('db_error');
    }

    header('Location: heroes_list.php');
    exit;
}

include 'header.php';
?>
<h2>Crear héroe</h2>
<form method="post" action="hero_create.php" enctype="multipart/form-data">
    <label>Nombre:<br>
        <input type="text" name="nombre" required>
    </label><br><br>
    <label>Rol:<br>
        <select name="rol" required>
            <option value="">Selecciona</option>
            <option value="Tanque">Tanque</option>
            <option value="Daño">Daño</option>
            <option value="Apoyo">Apoyo</option>
        </select>
    </label><br><br>
    <label>Descripción:<br>
        <textarea name="descripcion" rows="4" cols="40"></textarea>
    </label><br><br>
    <label>Imagen del héroe:<br>
        <input type="file" name="imagen" accept="image/*">
    </label><br><br>
    <button type="submit">Guardar</button>
</form>
</main>
</body>
</html>
