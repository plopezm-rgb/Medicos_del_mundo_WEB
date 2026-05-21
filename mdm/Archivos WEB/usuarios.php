<?php
require_once "conexion.php"; // Limpiado duplicado
require_once "Usuario.php";
require_once "auth_admin.php";
restringirAcceso(["administradora"]);

if (isset($_GET["eliminar"])) {
    $idEliminar = (int) $_GET["eliminar"];
    Usuario::eliminar($conexion, $idEliminar);
    header("Location: usuarios.php");
    exit;
}

$usuarios = Usuario::obtenerTodosConRol($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarias</title>
    <link rel="stylesheet" href="estilos.css?v=2">
</head>
<body>
<header> 
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Gestión de usuarias</h1>
    </section>
</header>

<main>
    <section class="acciones-admin">
        <a href="crear_usuario.php" class="boton-principal">+ Crear nueva usuaria</a>
        <a href="admin.php" class="boton-secundario">← Volver al panel</a>
    </section>

    <section class="detalle">
        <?php foreach ($usuarios as $usuario): ?>
            <p>
                <strong><?php echo htmlspecialchars($usuario["nombre"]); ?></strong>
                - <?php echo htmlspecialchars($usuario["email"]); ?>
                - <span class="etiqueta"><?php echo htmlspecialchars($usuario["rol"]); ?></span>
                
                <a href="usuarios.php?eliminar=<?php echo $usuario["id_usuario"]; ?>"
                   class="boton-borrar"
                   onclick="return confirm('¿Seguro que quieres eliminar esta usuaria?')">
                   Eliminar
                </a>
            </p>
        <?php endforeach; ?>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>