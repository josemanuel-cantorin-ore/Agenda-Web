<?php
// ============================================================
// AGREGAR USUARIO - ADMINISTRACIÓN (solo admin)
// Archivo: agregar_usuario.php
// Descripción: Formulario para crear una nueva cuenta de usuario
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$errores = [];
$datos   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre'   => trim($_POST['nombre']   ?? ''),
        'email'    => trim($_POST['email']    ?? ''),
        'usuario'  => trim($_POST['usuario']  ?? ''),
        'password' => $_POST['password']      ?? '',
        'rol'      => $_POST['rol']           ?? 'user',
    ];

    if (!$datos['nombre'])  $errores[] = 'El nombre es obligatorio.';
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Email inválido.';
    if (!$datos['usuario']) $errores[] = 'El usuario es obligatorio.';
    if (strlen($datos['password']) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if (!in_array($datos['rol'], ['admin','user'])) $errores[] = 'Rol inválido.';

    if (empty($errores)) {
        $pdo = getConexion();

        // Verificar unicidad
        $chk = $pdo->prepare("SELECT id FROM usuarios WHERE email=? OR usuario=?");
        $chk->execute([$datos['email'], $datos['usuario']]);
        if ($chk->fetch()) {
            $errores[] = 'El email o usuario ya están en uso.';
        } else {
            $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, usuario, password, rol) VALUES (?,?,?,?,?)");
            $stmt->execute([$datos['nombre'], $datos['email'], $datos['usuario'], $hash, $datos['rol']]);
            $_SESSION['mensaje'] = 'Usuario creado correctamente.';
            header('Location: usuarios.php');
            exit;
        }
    }
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Nuevo Usuario</h1>
        <p class="page-sub">Crea una cuenta de acceso al sistema</p>
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
                   placeholder="Ej. María García"
                   value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="correo@ejemplo.com"
                   value="<?= htmlspecialchars($datos['email'] ?? '') ?>" required>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="usuario">Nombre de usuario *</label>
                <input type="text" id="usuario" name="usuario" class="form-control"
                       placeholder="nombre_usuario"
                       value="<?= htmlspecialchars($datos['usuario'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" class="form-control">
                    <option value="user"  <?= ($datos['rol'] ?? 'user') === 'user'  ? 'selected' : '' ?>>Usuario</option>
                    <option value="admin" <?= ($datos['rol'] ?? '')      === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Mínimo 6 caracteres" required>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="usuarios.php" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

</main>
</body>
</html>
