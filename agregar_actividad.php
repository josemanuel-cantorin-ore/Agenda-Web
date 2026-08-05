<?php
// ============================================================
// AGREGAR ACTIVIDAD - CRUD: CREATE
// Archivo: agregar_actividad.php
// Descripción: Formulario para registrar una nueva actividad
// ============================================================

require_once 'header.php';
require_once 'conexion.php';

$errores = [];
$datos   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'titulo'      => trim($_POST['titulo']      ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'fecha'       => $_POST['fecha']            ?? '',
        'hora'        => $_POST['hora']             ?? '',
        'prioridad'   => $_POST['prioridad']        ?? 'media',
        'estado'      => $_POST['estado']           ?? 'pendiente',
    ];

    if (!$datos['titulo'])  $errores[] = 'El título es obligatorio.';
    if (!$datos['fecha'])   $errores[] = 'La fecha es obligatoria.';
    if (!in_array($datos['prioridad'], ['baja','media','alta'])) $errores[] = 'Prioridad inválida.';
    if (!in_array($datos['estado'], ['pendiente','en_progreso','completada'])) $errores[] = 'Estado inválido.';

    if (empty($errores)) {
        $pdo  = getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO actividades (usuario_id, titulo, descripcion, fecha, hora, prioridad, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_SESSION['usuario_id'],
            $datos['titulo'],
            $datos['descripcion'],
            $datos['fecha'],
            $datos['hora'] ?: null,
            $datos['prioridad'],
            $datos['estado'],
        ]);
        $_SESSION['mensaje'] = 'Actividad creada correctamente.';
        header('Location: actividades.php');
        exit;
    }
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Nueva Actividad</h1>
        <p class="page-sub">Registra una tarea o evento en tu agenda</p>
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
                   placeholder="Nombre de la actividad"
                   value="<?= htmlspecialchars($datos['titulo'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control"
                      rows="4" placeholder="Detalles de la actividad..."
                      style="resize:vertical;"><?= htmlspecialchars($datos['descripcion'] ?? '') ?></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" class="form-control"
                       value="<?= htmlspecialchars($datos['fecha'] ?? date('Y-m-d')) ?>" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" class="form-control"
                       value="<?= htmlspecialchars($datos['hora'] ?? '') ?>">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad" class="form-control">
                    <?php foreach (['baja','media','alta'] as $p): ?>
                        <option value="<?= $p ?>" <?= ($datos['prioridad'] ?? 'media') === $p ? 'selected' : '' ?>>
                            <?= ucfirst($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <?php foreach (['pendiente','en_progreso','completada'] as $e): ?>
                        <option value="<?= $e ?>" <?= ($datos['estado'] ?? 'pendiente') === $e ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_',' ',$e)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Guardar Actividad</button>
            <a href="actividades.php" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

</main>
</body>
</html>
