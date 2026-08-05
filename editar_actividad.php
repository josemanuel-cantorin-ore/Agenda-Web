<?php
// ============================================================
// EDITAR ACTIVIDAD - CRUD: UPDATE
// Archivo: editar_actividad.php
// Descripción: Formulario para modificar una actividad existente
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

$pdo = getConexion();
$uid = $_SESSION['usuario_id'];
$id  = (int)($_GET['id'] ?? 0);

// Obtener actividad y verificar pertenencia al usuario
$stmt = $pdo->prepare("SELECT * FROM actividades WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $uid]);
$actividad = $stmt->fetch();

if (!$actividad) {
    $_SESSION['mensaje'] = 'Actividad no encontrada.';
    header('Location: actividades.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'titulo'      => trim($_POST['titulo']      ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'fecha'       => $_POST['fecha']            ?? '',
        'hora'        => $_POST['hora']             ?? '',
        'prioridad'   => $_POST['prioridad']        ?? 'media',
        'estado'      => $_POST['estado']           ?? 'pendiente',
    ];

    if (!$datos['titulo']) $errores[] = 'El título es obligatorio.';
    if (!$datos['fecha'])  $errores[] = 'La fecha es obligatoria.';

    if (empty($errores)) {
        $stmt = $pdo->prepare(
            "UPDATE actividades SET titulo=?, descripcion=?, fecha=?, hora=?, prioridad=?, estado=?
             WHERE id = ? AND usuario_id = ?"
        );
        $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['fecha'],
            $datos['hora'] ?: null,
            $datos['prioridad'],
            $datos['estado'],
            $id,
            $uid,
        ]);
        $_SESSION['mensaje'] = 'Actividad actualizada correctamente.';
        header('Location: actividades.php');
        exit;
    }
    $actividad = array_merge($actividad, $datos);
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Editar Actividad</h1>
        <p class="page-sub">Modifica los datos de la actividad seleccionada</p>
    </div>
    <a href="actividades.php" class="btn btn-ghost">← Volver</a>
</div>

<?php if ($errores): ?>
    <div class="alert alert-error">
        <?php foreach ($errores as $e): ?><div>⚠ <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width:640px;">
    <form method="POST">
        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" class="form-control"
                   value="<?= htmlspecialchars($actividad['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control"
                      rows="4" style="resize:vertical;"><?= htmlspecialchars($actividad['descripcion'] ?? '') ?></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" class="form-control"
                       value="<?= htmlspecialchars($actividad['fecha']) ?>" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" class="form-control"
                       value="<?= htmlspecialchars($actividad['hora'] ?? '') ?>">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad" class="form-control">
                    <?php foreach (['baja','media','alta'] as $p): ?>
                        <option value="<?= $p ?>" <?= $actividad['prioridad'] === $p ? 'selected' : '' ?>>
                            <?= ucfirst($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <?php foreach (['pendiente','en_progreso','completada'] as $e): ?>
                        <option value="<?= $e ?>" <?= $actividad['estado'] === $e ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_',' ',$e)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="actividades.php" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

</main>
</body>
</html>
