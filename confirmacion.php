<?php
require_once "config/db.php";
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT p.*, f.codigo FROM pedidos p LEFT JOIN facturas f ON f.pedido_id=p.id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) die("Pedido no encontrado.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido confirmado</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrap">
    <div class="panel">
        <h1>Pedido confirmado</h1>
        <p>Tu pedido fue registrado correctamente.</p>
        <h2>Pedido #<?= $p['id'] ?></h2>
        <p><b>Estado:</b> <?= $p['estado'] ?></p>
        <p><b>Pago:</b> <?= $p['metodo_pago'] ?></p>
        <p><b>Factura:</b> <?= $p['codigo'] ?></p>
        <p><b>Total:</b> Bs. <?= number_format($p['total'],2) ?></p>
        <?php if($p['tipo'] === 'delivery'): ?>
            <p>Consulta tu delivery con el número: <b><?= $p['id'] ?></b></p>
            <a class="btn primary" href="estado.php">Consultar estado</a>
        <?php endif; ?>
        <a class="btn" href="index.php">Volver al menú</a>
    </div>
</div>
</body>
</html>
