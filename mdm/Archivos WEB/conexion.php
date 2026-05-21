<?php
$host = "192.168.4.14";
$puerto = "5432";
$basedatos = "mdm";
$usuario = "postgres";
$contrasena = "PostgreSQL";

try {
    $conexion = new PDO("pgsql:host=$host;port=$puerto;dbname=$basedatos", $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>