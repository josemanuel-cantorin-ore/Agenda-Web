<?php
// ============================================================
// ELIMINAR USUARIO - ADMINISTRACIÓN (solo admin)
// Archivo: eliminar_usuario.php
// Descripción: Elimina un usuario y todas sus actividades
// ============================================================

session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = getConexion();
$id  = (int)($_GET['id'] ?? 0);

// No permitir que el admin se elimine a sí mismo
if ($id > 0 && $id !== $_SESSION['usuario_id']) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensaje'] = 'Usuario eliminado correctamente.';
}

header('Location: usuarios.php');
exit;
