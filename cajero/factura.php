<?php
require_once "../config/db.php";
require_once "../config/helpers.php";
require_login('cajero');

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, f.codigo FROM pedidos p LEFT JOIN facturas f ON f.pedido_id=p.id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

$detalle = $pdo->prepare("SELECT d.*, pr.nombre FROM detalle_pedidos d JOIN productos pr ON pr.id=d.producto_id WHERE d.pedido_id=?");
$detalle->execute([$id]);
$items = $detalle->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="wrap">
    <div class="receipt">
        <center><b>COFFESTAR</b><br>NIT: 12345678</center>
        <hr>
        Factura: <?= $p['codigo'] ?><br>
        Pedido: #<?= $p['id'] ?><br>
        Fecha: <?= $p['fecha_creacion'] ?><br>
        Pago: <?= $p['metodo_pago'] ?><br>
        <hr>
        <?php foreach($items as $i): ?>
            <?= $i['cantidad'] ?> <?= $i['nombre'] ?> <?= money($i['subtotal']) ?><br>
        <?php endforeach; ?>
        <hr>
        <b>Total <?= money($p['total']) ?></b>
        <hr>
        <center>Gracias por su compra</center>
    </div>
    <br>
    <button onclick="window.print()">Imprimir</button>
    <a class="btn" href="panel.php">Volver</a>
</div>
</body>
</html>
