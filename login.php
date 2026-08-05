<?php
// ============================================================
// LOGIN - INICIO DE SESIÓN
// Archivo: login.php
// Descripción: Formulario de autenticación con credenciales seguras
// ============================================================

session_start();
require_once 'conexion.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario && $password) {
        $pdo  = getConexion();
        $stmt = $pdo->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE (usuario = ? OR email = ?) AND activo = 1");
        $stmt->execute([$usuario, $usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario_id']   = $user['id'];
            $_SESSION['usuario_nombre'] = $user['nombre'];
            $_SESSION['usuario_rol']  = $user['rol'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Electrónica — Ingresar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:    #0a0a14;
            --bg-card:    rgba(255,255,255,0.05);
            --border:     rgba(255,255,255,0.08);
            --accent1:    #7c5cfc;
            --accent2:    #2dd4bf;
            --text-main:  #e8e8f0;
            --text-muted: #7a7a9a;
            --error:      #f87171;
            --glass-blur: blur(20px);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated background orbs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: drift 12s ease-in-out infinite alternate;
        }
        body::before {
            width: 500px; height: 500px;
            background: var(--accent1);
            top: -150px; left: -150px;
        }
        body::after {
            width: 400px; height: 400px;
            background: var(--accent2);
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }
        @keyframes drift { from { transform: translate(0,0) scale(1); } to { transform: translate(30px,20px) scale(1.1); } }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            backdrop-filter: var(--glass-blur);
            border-radius: 24px;
            padding: 48px 40px;
        }

        .brand {
            text-align: center;
            margin-bottom: 36px;
        }
        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
        }
        .brand h1 {
            color: var(--text-main);
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .brand p {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 4px;
        }

        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .field input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--text-main);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color .2s, background .2s;
            outline: none;
        }
        .field input:focus {
            border-color: var(--accent1);
            background: rgba(124,92,252,0.06);
        }
        .field input::placeholder { color: var(--text-muted); }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--accent1), #5b3fd4);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            margin-top: 8px;
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .alert-error {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: 10px;
            color: var(--error);
            font-size: 13px;
            padding: 12px 14px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        .footer-note {
            text-align: center;
            margin-top: 28px;
            color: var(--text-muted);
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="brand">
            <div class="brand-icon">📅</div>
            <h1>Agenda Electrónica</h1>
            <p>Accede a tu espacio personal</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="field">
                <label for="usuario">Usuario o Email</label>
                <input type="text" id="usuario" name="usuario"
                       placeholder="tu_usuario"
                       value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                       required>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <p class="footer-note">Agenda Electrónica &copy; <?= date('Y') ?></p>
    </div>
</div>
</body>
</html>
