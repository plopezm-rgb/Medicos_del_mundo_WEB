<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["rol"] !== "editora" && $_SESSION["rol"] !== "administradora") {
    die("No tienes permisos para acceder a esta página.");
}
?>