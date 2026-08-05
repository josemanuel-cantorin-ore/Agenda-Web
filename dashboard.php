<?php
// ============================================================
// DASHBOARD - PANEL PRINCIPAL
// Archivo: dashboard.php
// Descripción: Vista general con estadísticas y actividades próximas
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

$pdo = getConexion();
$uid = $_SESSION['usuario_id'];

$total    = $pdo->prepare("SELECT COUNT(*) FROM actividades WHERE usuario_id = ?");
$total->execute([$uid]); $total = $total->fetchColumn();

$pendientes = $pdo->prepare("SELECT COUNT(*) FROM actividades WHERE usuario_id = ? AND estado = 'pendiente'");
$pendientes->execute([$uid]); $pendientes = $pendientes->fetchColumn();

$en_progreso = $pdo->prepare("SELECT COUNT(*) FROM actividades WHERE usuario_id = ? AND estado = 'en_progreso'");
$en_progreso->execute([$uid]); $en_progreso = $en_progreso->fetchColumn();

$completadas = $pdo->prepare("SELECT COUNT(*) FROM actividades WHERE usuario_id = ? AND estado = 'completada'");
$completadas->execute([$uid]); $completadas = $completadas->fetchColumn();

$proximas = $pdo->prepare("SELECT * FROM actividades WHERE usuario_id = ? AND fecha >= CURDATE() ORDER BY fecha ASC, hora ASC LIMIT 5");
$proximas->execute([$uid]);
$proximas = $proximas->fetchAll();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Bienvenido, <?= htmlspecialchars(explode(' ', $_SESSION['usuario_nombre'])[0]) ?> 👋</h1>
        <p class="page-sub"><?= date('l, d \d\e F \d\e Y') ?></p>
    </div>
    <a href="agregar_actividad.php" class="btn btn-primary">+ Nueva Actividad</a>
</div>

<!-- Stats Grid -->
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:28px;">

    <div class="card" style="border-color: rgba(124,92,252,0.2);">
        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); margin-bottom:12px;">Total</div>
        <div style="font-size:36px; font-weight:700; color:var(--accent1);"><?= $total ?></div>
        <div style="font-size:13px; color:var(--text-sub); margin-top:4px;">actividades</div>
    </div>

    <div class="card" style="border-color: rgba(107,107,138,0.2);">
        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); margin-bottom:12px;">Pendientes</div>
        <div style="font-size:36px; font-weight:700; color:var(--text-sub);"><?= $pendientes ?></div>
        <div style="font-size:13px; color:var(--text-muted); margin-top:4px;">por iniciar</div>
    </div>

    <div class="card" style="border-color: rgba(124,92,252,0.2);">
        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); margin-bottom:12px;">En Progreso</div>
        <div style="font-size:36px; font-weight:700; color:var(--accent1);"><?= $en_progreso ?></div>
        <div style="font-size:13px; color:var(--text-sub); margin-top:4px;">activas</div>
    </div>

    <div class="card" style="border-color: rgba(74,222,128,0.2);">
        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); margin-bottom:12px;">Completadas</div>
        <div style="font-size:36px; font-weight:700; color:var(--accent-grn);"><?= $completadas ?></div>
        <div style="font-size:13px; color:var(--text-sub); margin-top:4px;">finalizadas</div>
    </div>
</div>

<!-- Upcoming Activities -->
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:16px; font-weight:600;">Próximas Actividades</h2>
        <a href="actividades.php" style="font-size:13px; color:var(--accent1); text-decoration:none;">Ver todas →</a>
    </div>

    <?php if (empty($proximas)): ?>
        <div style="text-align:center; padding:40px 0; color:var(--text-muted);">
            <div style="font-size:32px; margin-bottom:10px;">📭</div>
            <p>No hay actividades próximas.</p>
            <a href="agregar_actividad.php" style="color:var(--accent1); font-size:13px; text-decoration:none;">Crear una ahora →</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Actividad</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proximas as $a): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($a['titulo']) ?></strong></td>
                    <td><?= date('d/m/Y', strtotime($a['fecha'])) ?></td>
                    <td><?= $a['hora'] ? date('H:i', strtotime($a['hora'])) : '—' ?></td>
                    <td><span class="badge badge-<?= $a['prioridad'] ?>"><?= ucfirst($a['prioridad']) ?></span></td>
                    <td><span class="badge badge-<?= $a['estado'] ?>"><?= ucfirst(str_replace('_',' ',$a['estado'])) ?></span></td>
                    <td>
                        <a href="editar_actividad.php?id=<?= $a['id'] ?>" class="btn btn-ghost" style="padding:6px 12px; font-size:12px;">Editar</a>
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
