<?php
// ============================================================
// LISTAR USUARIOS - ADMINISTRACIÓN (solo admin)
// Archivo: usuarios.php
// Descripción: Panel de gestión de cuentas de usuario
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pdo     = getConexion();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

$usuarios = $pdo->query("SELECT id, nombre, email, usuario, rol, activo, creado_en FROM usuarios ORDER BY creado_en DESC")->fetchAll();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de Usuarios</h1>
        <p class="page-sub"><?= count($usuarios) ?> usuario(s) registrado(s)</p>
    </div>
    <a href="agregar_usuario.php" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<?php if ($mensaje): ?>
    <div class="alert alert-success">✓ <?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registrado</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--accent1),var(--accent2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0;">
                            <?= strtoupper(substr($u['nombre'],0,1)) ?>
                        </div>
                        <span style="font-weight:500;"><?= htmlspecialchars($u['nombre']) ?></span>
                    </div>
                </td>
                <td style="color:var(--text-sub);"><?= htmlspecialchars($u['usuario']) ?></td>
                <td style="color:var(--text-sub);"><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="badge" style="<?= $u['rol']==='admin' ? 'background:rgba(124,92,252,0.12);color:var(--accent1)' : 'background:var(--bg-hover);color:var(--text-sub)' ?>">
                        <?= ucfirst($u['rol']) ?>
                    </span>
                </td>
                <td>
                    <span class="badge" style="<?= $u['activo'] ? 'background:rgba(74,222,128,0.12);color:var(--accent-grn)' : 'background:rgba(248,113,113,0.12);color:var(--accent-red)' ?>">
                        <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
                <td style="color:var(--text-muted); font-size:13px;"><?= date('d/m/Y', strtotime($u['creado_en'])) ?></td>
                <td style="text-align:right;">
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                            <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-ghost" style="padding:6px 12px; font-size:12px;">✏ Editar</a>
                            <a href="eliminar_usuario.php?id=<?= $u['id'] ?>"
                               class="btn btn-danger" style="padding:6px 12px; font-size:12px;"
                               onclick="return confirm('¿Eliminar este usuario y todas sus actividades?')">🗑</a>
                        <?php else: ?>
                            <span style="font-size:12px; color:var(--text-muted); padding:6px 12px;">Tú</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</main>
</body>
</html>
