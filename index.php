<?php
require_once "config/db.php";
$productos = $pdo->query("SELECT * FROM productos ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$mesas = $pdo->query("SELECT * FROM mesas WHERE estado='Libre' ORDER BY numero ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Coffestar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="top">
        <div class="logo">Coffee<span>star</span></div>
        <nav>
            <a class="btn primary" href="index.php">Cliente</a>
            <a class="btn" href="estado.php">Estado del pedido</a>
            <a class="btn" href="login.php">Ingresar Personal</a>
            <a class="btn" href="tecnologias.php">Tecnologías</a>
        </nav>
    </div>
</header>

<main class="wrap">
    <section class="hero">
        <div>
            <h1>Coffestar</h1>
            <p>Elige tus productos disponibles y completa tu pedido.</p>
        </div>
    </section>

    <h2>Productos disponibles</h2>
    <div class="grid">
        <?php foreach($productos as $p): ?>
            <div class="card product">
                <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                <div class="between">
                    <span class="price">Bs. <?= number_format($p['precio'],2) ?></span>
                    <button onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>', <?= $p['precio'] ?>, <?= $p['tiempo_preparacion'] ?>)">Agregar</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <section class="panel" style="margin-top:28px">
        <h2>Tu pedido</h2>
        <form action="procesar_pedido.php" method="POST" onsubmit="setTimeout(limpiarCarrito, 300)">
            <input type="hidden" name="cart" id="cartInput">

            <div class="field">
                <label>Tipo de pedido</label>
                <select name="tipo" required>
                    <option value="mesa">Consumir en local</option>
                    <option value="llevar">Para llevar</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>

            <div class="grid2">
                <div class="field">
                    <label>Nombre</label>
                    <input name="cliente_nombre" placeholder="Nombre del cliente">
                </div>
                <div class="field">
                    <label>Teléfono</label>
                    <input name="telefono" placeholder="Opcional">
                </div>
            </div>

            <div id="mesaBox" class="field">
                <label>Mesa disponible</label>
                <select name="mesa_id">
                    <?php foreach($mesas as $m): ?>
                        <option value="<?= $m['id'] ?>">Mesa <?= $m['numero'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="muted">Solo aparecen mesas libres.</div>
            </div>

            <div id="deliveryBox" style="display:none">
                <h3>Datos delivery</h3>
                <div class="grid2">
                    <div class="field"><label>Dirección</label><input name="direccion"></div>
                    <div class="field"><label>Referencia</label><input name="referencia"></div>
                </div>
                <div class="grid2">
                    <div class="field"><label>NIT / CI</label><input name="nit_ci"></div>
                    <div class="field"><label>Razón social</label><input name="razon_social"></div>
                </div>
            </div>

            <h3>Productos seleccionados</h3>
            <div id="cartBox"></div>
            <div class="between">
                <b>Total</b>
                <b id="cartTotal" class="total">Bs. 0.00</b>
            </div>

            <h3>Método de pago</h3>
            <label class="cart-item"><input type="radio" name="metodo_pago" value="QR" checked> QR</label>
            <label class="cart-item"><input type="radio" name="metodo_pago" value="Efectivo"> Efectivo</label>
            <label class="cart-item"><input type="radio" name="metodo_pago" value="Tarjeta/Débito"> Tarjeta / Débito</label>

            <button class="primary" type="submit" style="width:100%;margin-top:15px">Confirmar pedido</button>
        </form>
    </section>
</main>
<script src="assets/js/app.js"></script>
</body>
</html>
