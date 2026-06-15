<?php
// config/helpers.php
session_start();

function redirect($url) {
    header("Location: $url");
    exit;
}

function is_logged() {
    return isset($_SESSION['usuario']);
}

function require_login($rol = null) {
    if (!is_logged()) {
        redirect("../login.php");
    }

    if ($rol && $_SESSION['rol'] !== $rol) {
        redirect("../login.php");
    }
}

function money($n) {
    return "Bs. " . number_format((float)$n, 2);
}

function factura_codigo($pedido_id) {
    return "FAC-" . date("Y") . "-" . str_pad($pedido_id, 6, "0", STR_PAD_LEFT);
}
?>
