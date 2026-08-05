<?php
// ============================================================
// HEADER / NAVBAR - PLANTILLA COMPARTIDA
// Archivo: header.php
// Descripción: Barra de navegación incluida en todas las páginas
// ============================================================

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Electrónica</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:    #0a0a14;
            --bg-surface: #12121f;
            --bg-card:    rgba(255,255,255,0.04);
            --bg-hover:   rgba(255,255,255,0.07);
            --border:     rgba(255,255,255,0.08);
            --accent1:    #7c5cfc;
            --accent2:    #2dd4bf;
            --accent-red: #f87171;
            --accent-yel: #fbbf24;
            --accent-grn: #4ade80;
            --text-main:  #e8e8f0;
            --text-muted: #6b6b8a;
            --text-sub:   #9898b8;
            --radius-sm:  8px;
            --radius-md:  14px;
            --radius-lg:  20px;
            --shadow:     0 8px 32px rgba(0,0,0,0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
            padding: 24px 16px;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 12px 24px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 16px;
        }
        .sidebar-brand .icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sidebar-brand .name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }
        .sidebar-brand .tagline {
            font-size: 11px;
            color: var(--text-muted);
        }

        .nav-section-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0 12px;
            margin: 12px 0 6px;
        }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-sub);
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            transition: background .15s, color .15s;
            margin-bottom: 2px;
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--text-main); }
        .nav-link.active {
            background: rgba(124,92,252,0.12);
            color: var(--accent1);
            font-weight: 500;
        }
        .nav-link .nav-icon { width: 18px; text-align: center; }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding-top: 16px;
        }
        .user-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
        }
        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600;
            flex-shrink: 0;
        }
        .user-info .uname { font-size: 13px; font-weight: 500; color: var(--text-main); }
        .user-info .urole { font-size: 11px; color: var(--text-muted); text-transform: capitalize; }

        /* === MAIN CONTENT === */
        .main {
            margin-left: 240px;
            flex: 1;
            padding: 32px;
            min-height: 100vh;
        }

        /* === SHARED COMPONENTS === */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px;
        }
        .page-title { font-size: 22px; font-weight: 600; color: var(--text-main); }
        .page-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            border: none;
            transition: opacity .15s, transform .1s;
            text-decoration: none;
        }
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--accent1); color: #fff; }
        .btn-danger  { background: rgba(248,113,113,0.15); color: var(--accent-red); border: 1px solid rgba(248,113,113,0.25); }
        .btn-ghost   { background: var(--bg-hover); color: var(--text-sub); }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-alta     { background: rgba(248,113,113,0.12); color: var(--accent-red); }
        .badge-media    { background: rgba(251,191,36,0.12);  color: var(--accent-yel); }
        .badge-baja     { background: rgba(74,222,128,0.12);  color: var(--accent-grn); }
        .badge-pendiente    { background: rgba(107,107,138,0.15); color: var(--text-sub); }
        .badge-en_progreso  { background: rgba(124,92,252,0.12); color: var(--accent1); }
        .badge-completada   { background: rgba(74,222,128,0.12);  color: var(--accent-grn); }

        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-success { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2); color: var(--accent-grn); }
        .alert-error   { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.2); color: var(--accent-red); }

        /* Form styles */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px; font-weight: 500;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 7px;
        }
        .form-control {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            color: var(--text-main);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .2s;
        }
        .form-control:focus { border-color: var(--accent1); background: rgba(124,92,252,0.04); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: #1a1a2e; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }
        tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background .1s; }
        tbody tr:hover { background: var(--bg-hover); }
        tbody td { padding: 14px 16px; font-size: 14px; color: var(--text-main); }
        tbody tr:last-child { border-bottom: none; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main { margin-left: 200px; padding: 20px; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="icon">📅</div>
        <div>
            <div class="name">Agenda</div>
            <div class="tagline">Electrónica</div>
        </div>
    </div>

    <span class="nav-section-title">Principal</span>
    <a href="dashboard.php" class="nav-link <?= $pagina_actual === 'dashboard.php' ? 'active' : '' ?>">
        <span class="nav-icon">⊞</span> Dashboard
    </a>
    <a href="actividades.php" class="nav-link <?= $pagina_actual === 'actividades.php' ? 'active' : '' ?>">
        <span class="nav-icon">✓</span> Actividades
    </a>
    <a href="agregar_actividad.php" class="nav-link <?= $pagina_actual === 'agregar_actividad.php' ? 'active' : '' ?>">
        <span class="nav-icon">+</span> Nueva Actividad
    </a>

    <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
    <span class="nav-section-title">Administración</span>
    <a href="usuarios.php" class="nav-link <?= $pagina_actual === 'usuarios.php' ? 'active' : '' ?>">
        <span class="nav-icon">👤</span> Usuarios
    </a>
    <a href="agregar_usuario.php" class="nav-link <?= $pagina_actual === 'agregar_usuario.php' ? 'active' : '' ?>">
        <span class="nav-icon">+</span> Nuevo Usuario
    </a>
    <?php endif; ?>

    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
            <div class="user-info">
                <div class="uname"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></div>
                <div class="urole"><?= $_SESSION['usuario_rol'] ?></div>
            </div>
        </div>
        <a href="logout.php" class="nav-link" style="color: var(--accent-red);">
            <span class="nav-icon">⏻</span> Cerrar Sesión
        </a>
    </div>
</aside>

<main class="main">
