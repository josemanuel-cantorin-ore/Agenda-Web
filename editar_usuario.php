<?php
// ============================================================
// EDITAR USUARIO - ADMINISTRACIÓN (solo admin)
// Archivo: editar_usuario.php
// Descripción: Formulario para modificar los datos de un usuario
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pdo = getConexion();
$id  = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id, nombre, email, usuario, rol, activo FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    $_SESSION['mensaje'] = 'Usuario no encontrado.';
    header('Location: usuarios.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre'  => trim($_POST['nombre']  ?? ''),
        'email'   => trim($_POST['email']   ?? ''),
        'usuario' => trim($_POST['usuario'] ?? ''),
        'rol'     => $_POST['rol']          ?? 'user',
        'activo'  => isset($_POST['activo']) ? 1 : 0,
        'password'=> $_POST['password']     ?? '',
    ];

    if (!$datos['nombre']) $errores[] = 'El nombre es obligatorio.';
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Email inválido.';
    if (!$datos['usuario']) $errores[] = 'El usuario es obligatorio.';

    if (empty($errores)) {
        // Verificar unicidad excluyendo el usuario actual
        $chk = $pdo->prepare("SELECT id FROM usuarios WHERE (email=? OR usuario=?) AND id != ?");
        $chk->execute([$datos['email'], $datos['usuario'], $id]);
        if ($chk->fetch()) {
            $errores[] = 'El email o usuario ya están en uso.';
        } else {
            if ($datos['password']) {
                // Actualizar con nueva contraseña
                $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?,email=?,usuario=?,rol=?,activo=?,password=? WHERE id=?");
                $stmt->execute([$datos['nombre'],$datos['email'],$datos['usuario'],$datos['rol'],$datos['activo'],$hash,$id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?,email=?,usuario=?,rol=?,activo=? WHERE id=?");
                $stmt->execute([$datos['nombre'],$datos['email'],$datos['usuario'],$datos['rol'],$datos['activo'],$id]);
            }
            $_SESSION['mensaje'] = 'Usuario actualizado correctamente.';
            header('Location: usuarios.php');
            exit;
        }
    }
    $usuario = array_merge($usuario, $datos);
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Editar Usuario</h1>
        <p class="page-sub">Modifica los datos de la cuenta</p>
    </div>
    <a href="usuarios.php" class="btn btn-ghost">← Volver</a>
</div>

<?php if ($errores): ?>
    <div class="alert alert-error">
        <?php foreach ($errores as $e): ?><div>⚠ <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width:560px;">
    <form method="POST">
        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="usuario">Nombre de usuario *</label>
                <input type="text" id="usuario" name="usuario" class="form-control"
                       value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" class="form-control">
                    <option value="user"  <?= $usuario['rol'] === 'user'  ? 'selected' : '' ?>>Usuario</option>
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Nueva contraseña <span style="color:var(--text-muted); font-size:11px;">(dejar en blanco para no cambiar)</span></label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="••••••••">
        </div>
        <div class="form-group" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" id="activo" name="activo" value="1"
                   <?= $usuario['activo'] ? 'checked' : '' ?>
                   style="width:16px; height:16px; accent-color:var(--accent1);">
            <label for="activo" style="text-transform:none; letter-spacing:0; font-size:14px; margin:0; color:var(--text-main);">
                Cuenta activa
            </label>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="usuarios.php" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

</main>
</body>
</html>
