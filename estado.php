<?php
require_once "config/db.php";
$pedido = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Pedido</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header><div class="top"><div class="logo">Coffee<span>star</span></div><nav><a class="btn" href="index.php">Cliente</a><a class="btn primary" href="estado.php">Estado del pedido</a><a class="btn" href="login.php">Ingresar Personal</a></nav></div></header>
<main class="wrap">
    <div class="panel">
        <h1>Consultar estado de delivery</h1>
        <form method="GET">
            <div class="field">
                <label>Número de pedido</label>
                <input name="id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
            </div>
            <button class="primary">Consultar</button>
        </form>

        <?php if($pedido): ?>
            <?php if($pedido['tipo'] !== 'delivery'): ?>
                <div class="order">Este pedido no es delivery. Se gestiona directamente en local.</div>
            <?php else: ?>
                <div class="order">
                    <h2>Delivery #<?= $pedido['id'] ?></h2>
                    <p>Estado: <span class="badge ok"><?= $pedido['estado'] ?></span></p>
                    <div class="grid2">
                        <div class="box <?= in_array($pedido['estado'], ['Preparando','En camino','Entregado']) ? 'free' : '' ?>">✓ Preparando</div>
                        <div class="box <?= in_array($pedido['estado'], ['En camino','Entregado']) ? 'free' : '' ?>">✓ En camino</div>
                        <div class="box <?= $pedido['estado'] === 'Entregado' ? 'free' : '' ?>">✓ Entregado</div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
