<?php
require_once "../config/db.php";
require_once "../config/helpers.php";
require_login('cajero');

$pedidos = $pdo->query("SELECT * FROM pedidos WHERE estado!='Cancelado' ORDER BY id DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
$mesas = $pdo->query("SELECT * FROM mesas ORDER BY numero ASC")->fetchAll(PDO::FETCH_ASSOC);
$ventas = $pdo->query("SELECT COALESCE(SUM(total),0) total FROM pedidos WHERE DATE(fecha_creacion)=CURDATE() AND estado!='Cancelado'")->fetch(PDO::FETCH_ASSOC)['total'];
$activos = $pdo->query("SELECT COUNT(*) c FROM pedidos WHERE estado NOT IN ('Finalizado','Cancelado')")->fetch(PDO::FETCH_ASSOC)['c'];
$caja = $pdo->query("SELECT * FROM caja ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cajero Coffestar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><div class="top"><div class="logo">Coffee<span>star</span></div><nav><a class="btn" href="../logout.php">Salir</a></nav></div></header>
<main class="wrap">
    <h1>Panel Cajero</h1>
    <div class="grid4">
        <div class="panel"><span>Ventas hoy</span><h2>Bs. <?= number_format($ventas,2) ?></h2></div>
        <div class="panel"><span>Pedidos activos</span><h2><?= $activos ?></h2></div>
        <div class="panel"><span>Caja</span><h2><?= $caja && $caja['estado']=='Abierta' ? 'Abierta' : 'Cerrada' ?></h2></div>
        <div class="panel"><span>Mesas</span><h2>10</h2></div>
    </div>

    <div class="grid2" style="margin-top:18px">
        <div class="panel">
            <h2>Control de caja</h2>
            <form action="caja.php" method="POST">
                <div class="field"><label>Monto inicial</label><input name="monto" type="number" step="0.01" value="0"></div>
                <button class="ok" name="accion" value="abrir">Abrir caja</button>
                <button class="bad" name="accion" value="cerrar">Cerrar caja / arqueo</button>
            </form>
        </div>
        <div class="panel">
            <h2>Mesas en tiempo real</h2>
            <div class="grid2">
            <?php foreach($mesas as $m): ?>
                <div class="table-card <?= $m['estado']=='Libre'?'free':($m['estado']=='Preparando'?'prep':'busy') ?>">
                    <b>Mesa <?= $m['numero'] ?></b><br><?= $m['estado'] ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top:18px">
        <h2>Órdenes y pagos</h2>
        <?php foreach($pedidos as $p): ?>
            <div class="order">
                <div class="between">
                    <div>
                        <b>Pedido #<?= $p['id'] ?></b>
                        <span class="badge warn"><?= $p['estado'] ?></span>
                        <span class="badge"><?= $p['tipo'] === 'mesa' ? 'Mesa '.$p['mesa_id'] : $p['tipo'] ?></span>
                    </div>
                    <b>Bs. <?= number_format($p['total'],2) ?></b>
                </div>
                <p>Cliente: <?= htmlspecialchars($p['cliente_nombre']) ?> · Pago: <?= $p['metodo_pago'] ?> · Mesero: <?= $p['mesero'] ?></p>
                <p>Factura generada automáticamente.</p>
                <a class="btn info" href="factura.php?id=<?= $p['id'] ?>">Ver / reimprimir factura</a>
                <?php if($p['estado'] !== 'Entregado' && $p['estado'] !== 'Finalizado'): ?>
                    <a class="btn ok" href="actualizar_estado.php?id=<?= $p['id'] ?>&estado=Entregado">Marcar entregado</a>
                <?php endif; ?>
                <?php if($p['estado'] === 'Entregado'): ?>
                    <a class="btn" href="actualizar_estado.php?id=<?= $p['id'] ?>&estado=Finalizado">Liberar mesa / finalizar</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
