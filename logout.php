<?php
require_once "config/helpers.php";
session_destroy();
redirect("login.php");
?>
