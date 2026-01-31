<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario'])) redirect_with_error('not_logged_in');
if ($_SESSION['usuario']['rol'] !== 'admin') redirect_with_error('no_permission');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect_with_error('invalid_id');

$sql = "DELETE FROM habilidades WHERE id=$id";
if (!mysqli_query($conn, $sql)) {
    redirect_with_error('db_error');
}

header('Location: abilities_list.php');
exit;
