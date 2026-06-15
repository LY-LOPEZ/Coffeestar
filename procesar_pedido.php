<?php
require_once "config/db.php";
require_once "config/helpers.php";

$cart = json_decode($_POST['cart'] ?? '[]', true);
if (!$cart || count($cart) == 0) {
    die("El carrito está vacío. <a href='index.php'>Volver</a>");
}

$tipo = $_POST['tipo'];
$mesa_id = ($tipo === 'mesa') ? ($_POST['mesa_id'] ?? null) : null;
$cliente = $_POST['cliente_nombre'] ?: "Cliente";
$telefono = $_POST['telefono'] ?? "";
$metodo = $_POST['metodo_pago'];
$direccion = $_POST['direccion'] ?? "";
$referencia = $_POST['referencia'] ?? "";
$nit_ci = $_POST['nit_ci'] ?? "";
$razon_social = $_POST['razon_social'] ?? "";

$total = 0;
$tiempo = 0;

foreach ($cart as $item) {
    $total += $item['precio'] * $item['cantidad'];
    $tiempo = max($tiempo, $item['tiempo']);
}

if ($tipo === 'delivery') {
    $total += 8;
    $tiempo += 12;
}

$meseros = ["Ana López", "Luis Pérez"];
$ultimo = (int)file_get_contents(__DIR__ . "/mesero_counter.txt");
$mesero = $meseros[$ultimo % count($meseros)];
file_put_contents(__DIR__ . "/mesero_counter.txt", $ultimo + 1);

$pdo->beginTransaction();

$stmt = $pdo->prepare("INSERT INTO pedidos 
(cliente_nombre, telefono, tipo, mesa_id, metodo_pago, total, estado, mesero, tiempo_estimado, direccion, referencia, nit_ci, razon_social)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute([$cliente, $telefono, $tipo, $mesa_id, $metodo, $total, 'Preparando', $mesero, $tiempo, $direccion, $referencia, $nit_ci, $razon_social]);

$pedido_id = $pdo->lastInsertId();

foreach ($cart as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $stmt = $pdo->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)");
    $stmt->execute([$pedido_id, $item['id'], $item['cantidad'], $item['precio'], $subtotal]);
}

$codigo = factura_codigo($pedido_id);
$stmt = $pdo->prepare("INSERT INTO facturas (pedido_id, codigo, nit_ci, razon_social, total) VALUES (?,?,?,?,?)");
$stmt->execute([$pedido_id, $codigo, $nit_ci, $razon_social ?: $cliente, $total]);

if ($tipo === 'mesa' && $mesa_id) {
    $stmt = $pdo->prepare("UPDATE mesas SET estado='Preparando' WHERE id=?");
    $stmt->execute([$mesa_id]);
}

$stmt = $pdo->prepare("SELECT id FROM clientes WHERE nombre=? LIMIT 1");
$stmt->execute([$cliente]);
$cliente_db = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente_db) {
    $stmt = $pdo->prepare("UPDATE clientes SET puntos=puntos+?, total_gastado=total_gastado+? WHERE id=?");
    $stmt->execute([(int)$total, $total, $cliente_db['id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, telefono, puntos, total_gastado) VALUES (?,?,?,?)");
    $stmt->execute([$cliente, $telefono, (int)$total, $total]);
}

$pdo->commit();

redirect("confirmacion.php?id=" . $pedido_id);
?>
