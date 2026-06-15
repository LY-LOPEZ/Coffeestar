<?php
require_once "../config/db.php";
require_once "../config/helpers.php";
require_login('cajero');

$id = $_GET['id'];
$estado = $_GET['estado'];

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if ($p) {
    $stmt = $pdo->prepare("UPDATE pedidos SET estado=?, fecha_entrega=IF(?='Entregado',NOW(),fecha_entrega) WHERE id=?");
    $stmt->execute([$estado, $estado, $id]);

    if ($estado === 'Finalizado' && $p['mesa_id']) {
        $stmt = $pdo->prepare("UPDATE mesas SET estado='Libre' WHERE id=?");
        $stmt->execute([$p['mesa_id']]);
    }

    if ($estado === 'Entregado' && $p['mesa_id']) {
        $stmt = $pdo->prepare("UPDATE mesas SET estado='Entregado' WHERE id=?");
        $stmt->execute([$p['mesa_id']]);
    }
}

redirect("panel.php");
?>
