<?php
require_once "config/db.php";
require_once "config/helpers.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario=? AND password=? LIMIT 1");
    $stmt->execute([$usuario, $password]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        $_SESSION['usuario'] = $u['usuario'];
        $_SESSION['nombre'] = $u['nombre'];
        $_SESSION['rol'] = $u['rol'];

        if ($u['rol'] === 'admin') redirect("admin/dashboard.php");
        if ($u['rol'] === 'cajero') redirect("cajero/panel.php");
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Coffestar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrap">
    <div class="panel" style="max-width:460px;margin:auto">
        <h1>Ingreso de personal</h1>
        <?php if($error): ?><div class="box"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="field"><label>Usuario</label><input name="usuario" placeholder="admin / cajero"></div>
            <div class="field"><label>Contraseña</label><input name="password" type="password" placeholder="admin123 / caja123"></div>
            <button class="primary" style="width:100%">Ingresar</button>
        </form>
        <p class="muted">admin/admin123 · cajero/caja123</p>
        <a href="index.php">Volver</a>
    </div>
</div>
</body>
</html>
