<?php
// ============================================================
// ELIMINAR ACTIVIDAD - CRUD: DELETE
// Archivo: eliminar_actividad.php
// Descripción: Elimina una actividad del usuario en sesión
// ============================================================

session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getConexion();
$uid = $_SESSION['usuario_id'];
$id  = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM actividades WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id, $uid]);
    $_SESSION['mensaje'] = 'Actividad eliminada correctamente.';
}

header('Location: actividades.php');
exit;
