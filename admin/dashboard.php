<?php
require_once "../config/db.php";
require_once "../config/helpers.php";
require_login('admin');

$ventasHoy = $pdo->query("SELECT COALESCE(SUM(total),0) total FROM pedidos WHERE DATE(fecha_creacion)=CURDATE() AND estado!='Cancelado'")->fetch(PDO::FETCH_ASSOC)['total'];
$ventasMes = $pdo->query("SELECT COALESCE(SUM(total),0) total FROM pedidos WHERE MONTH(fecha_creacion)=MONTH(CURDATE()) AND estado!='Cancelado'")->fetch(PDO::FETCH_ASSOC)['total'];
$pedidos = $pdo->query("SELECT * FROM pedidos ORDER BY id DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
$inventario = $pdo->query("SELECT * FROM inventario ORDER BY insumo ASC")->fetchAll(PDO::FETCH_ASSOC);
$clientes = $pdo->query("SELECT * FROM clientes ORDER BY total_gastado DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$facturas = $pdo->query("SELECT f.*, p.cliente_nombre FROM facturas f JOIN pedidos p ON p.id=f.pedido_id ORDER BY f.id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Coffestar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><div class="top"><div class="logo">Coffee<span>star</span></div><nav><a class="btn" href="../logout.php">Salir</a></nav></div></header>
<main class="wrap">
    <h1>Administrador</h1>
    <div class="grid4">
        <div class="panel"><span>Ventas hoy</span><h2><?= money($ventasHoy) ?></h2></div>
        <div class="panel"><span>Ventas mes</span><h2><?= money($ventasMes) ?></h2></div>
        <div class="panel"><span>Facturas</span><h2><?= count($facturas) ?></h2></div>
        <div class="panel"><span>Pedidos</span><h2><?= count($pedidos) ?></h2></div>
    </div>

    <div class="grid2" style="margin-top:18px">
        <div class="panel">
            <h2>Inventario</h2>
            <?php foreach($inventario as $i): ?>
                <div class="box <?= $i['stock'] <= $i['minimo'] ? 'stockLow' : 'stockOk' ?>">
                    <b><?= $i['insumo'] ?></b>
                    <span style="float:right"><?= $i['stock'] ?> <?= $i['unidad'] ?></span>
                    <br><span class="muted">Mínimo: <?= $i['minimo'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="panel">
            <h2>Clientes frecuentes</h2>
            <?php foreach($clientes as $c): ?>
                <div class="box">
                    <b><?= htmlspecialchars($c['nombre']) ?></b><br>
                    <?= $c['puntos'] ?> puntos · <?= money($c['total_gastado']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid2" style="margin-top:18px">
        <div class="panel">
            <h2>Historial de facturas</h2>
            <?php foreach($facturas as $f): ?>
                <div class="box">
                    <b><?= $f['codigo'] ?></b><br>
                    <?= htmlspecialchars($f['cliente_nombre']) ?> · <?= money($f['total']) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="panel">
            <h2>Registro completo</h2>
            <?php foreach($pedidos as $p): ?>
                <div class="box">
                    <b>#<?= $p['id'] ?></b> <?= $p['tipo'] ?> · <?= $p['estado'] ?><br>
                    <?= htmlspecialchars($p['cliente_nombre']) ?> · <?= money($p['total']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
</body>
</html>
