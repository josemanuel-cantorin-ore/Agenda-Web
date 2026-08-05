<?php
// ============================================================
// LISTAR ACTIVIDADES - CRUD: READ
// Archivo: actividades.php
// Descripción: Lista todas las actividades con filtros y búsqueda
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

$pdo  = getConexion();
$uid  = $_SESSION['usuario_id'];

$buscar  = trim($_GET['buscar'] ?? '');
$filtro  = $_GET['estado'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

$sql    = "SELECT * FROM actividades WHERE usuario_id = ?";
$params = [$uid];

if ($buscar) {
    $sql    .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}
if ($filtro && in_array($filtro, ['pendiente','en_progreso','completada'])) {
    $sql    .= " AND estado = ?";
    $params[] = $filtro;
}
$sql .= " ORDER BY fecha ASC, hora ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$actividades = $stmt->fetchAll();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Mis Actividades</h1>
        <p class="page-sub"><?= count($actividades) ?> resultado(s)</p>
    </div>
    <a href="agregar_actividad.php" class="btn btn-primary">+ Agregar</a>
</div>

<?php if ($mensaje): ?>
    <div class="alert alert-success">✓ <?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<!-- Filters -->
<div class="card" style="padding:16px 20px; margin-bottom:20px;">
    <form method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
        <input type="text" name="buscar" class="form-control"
               style="max-width:280px;"
               placeholder="🔍 Buscar actividad..."
               value="<?= htmlspecialchars($buscar) ?>">
        <select name="estado" class="form-control" style="max-width:180px;">
            <option value="">Todos los estados</option>
            <option value="pendiente"   <?= $filtro === 'pendiente'   ? 'selected' : '' ?>>Pendiente</option>
            <option value="en_progreso" <?= $filtro === 'en_progreso' ? 'selected' : '' ?>>En Progreso</option>
            <option value="completada"  <?= $filtro === 'completada'  ? 'selected' : '' ?>>Completada</option>
        </select>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <?php if ($buscar || $filtro): ?>
            <a href="actividades.php" class="btn btn-ghost">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <?php if (empty($actividades)): ?>
        <div style="text-align:center; padding:60px 0; color:var(--text-muted);">
            <div style="font-size:40px; margin-bottom:12px;">🗂</div>
            <p style="font-size:15px;">No se encontraron actividades.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actividades as $i => $a): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
                    <td>
                        <div style="font-weight:500;"><?= htmlspecialchars($a['titulo']) ?></div>
                        <?php if ($a['descripcion']): ?>
                            <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                                <?= htmlspecialchars(substr($a['descripcion'], 0, 60)) ?>…
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($a['fecha'])) ?></td>
                    <td><?= $a['hora'] ? date('H:i', strtotime($a['hora'])) : '—' ?></td>
                    <td><span class="badge badge-<?= $a['prioridad'] ?>"><?= ucfirst($a['prioridad']) ?></span></td>
                    <td><span class="badge badge-<?= $a['estado'] ?>"><?= ucfirst(str_replace('_',' ',$a['estado'])) ?></span></td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <a href="editar_actividad.php?id=<?= $a['id'] ?>" class="btn btn-ghost" style="padding:6px 12px; font-size:12px;">✏ Editar</a>
                            <a href="eliminar_actividad.php?id=<?= $a['id'] ?>"
                               class="btn btn-danger" style="padding:6px 12px; font-size:12px;"
                               onclick="return confirm('¿Eliminar esta actividad?')">🗑 Eliminar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</main>
</body>
</html>
