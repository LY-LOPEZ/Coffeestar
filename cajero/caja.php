<?php
require_once "../config/db.php";
require_once "../config/helpers.php";
require_login('cajero');

$accion = $_POST['accion'];

if ($accion === 'abrir') {
    $stmt = $pdo->prepare("INSERT INTO caja (monto_inicial, estado) VALUES (?, 'Abierta')");
    $stmt->execute([$_POST['monto']]);
}

if ($accion === 'cerrar') {
    $pdo->query("UPDATE caja SET estado='Cerrada', fecha_cierre=NOW() WHERE estado='Abierta'");
}

redirect("panel.php");
?>
