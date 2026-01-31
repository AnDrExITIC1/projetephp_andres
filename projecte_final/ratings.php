<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) redirect_with_error('not_logged_in');

$usuario_id = $_SESSION['usuario']['id'];
$heroe_id = isset($_GET['heroe_id']) ? (int)$_GET['heroe_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heroe_id = (int)($_POST['heroe_id'] ?? 0);
    $puntuacion  = (int)($_POST['puntuacion'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');

    if ($heroe_id <= 0 || $puntuacion < 1 || $puntuacion > 5) {
        redirect_with_error('empty_fields');
    }

    $comentario_esc = mysqli_real_escape_string($conn, $comentario);

    $sql = "SELECT id FROM valoraciones_heroes WHERE heroe_id=$heroe_id AND usuario_id=$usuario_id";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $vid = (int)$row['id'];
        $sql_upd = "UPDATE valoraciones_heroes
                    SET puntuacion=$puntuacion, comentario='$comentario_esc'
                    WHERE id=$vid";
        mysqli_query($conn, $sql_upd);
    } else {
        $sql_ins = "INSERT INTO valoraciones_heroes (heroe_id, usuario_id, puntuacion, comentario)
                    VALUES ($heroe_id, $usuario_id, $puntuacion, '$comentario_esc')";
        mysqli_query($conn, $sql_ins);
    }

    header('Location: heroes_list.php');
    exit;
}

$heroes_res = mysqli_query($conn, "SELECT id, nombre FROM heroes ORDER BY nombre ASC");
if (!$heroes_res) redirect_with_error('db_error');

include 'header.php';
?>
<h2>Valorar héroe</h2>
<form method="post" action="ratings.php">
    <label>Héroe:<br>
        <select name="heroe_id" required>
            <option value="">Selecciona héroe</option>
            <?php while ($h = mysqli_fetch_assoc($heroes_res)): ?>
                <option value="<?php echo $h['id']; ?>" <?php if($heroe_id==$h['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($h['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </label><br><br>
    <label>Puntuación (1-5):<br>
        <select name="puntuacion" required>
            <option value="">Selecciona</option>
            <?php for ($i=1;$i<=5;$i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
    </label><br><br>
    <label>Comentario (opcional):<br>
        <textarea name="comentario" rows="4" cols="40"></textarea>
    </label><br><br>
    <button type="submit">Guardar valoración</button>
</form>
</main>
</body>
</html>
