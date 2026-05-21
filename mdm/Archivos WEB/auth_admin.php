<?php
session_start();

function restringirAcceso($rolesPermitidos) {
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    // Comprobamos si el nombre del rol del usuario está en la lista permitida
    if (!in_array($_SESSION["rol"], $rolesPermitidos)) {
        echo "<script>alert('No tienes permiso para esto'); window.location.href='index.php';</script>";
        exit;
    }
}
?>