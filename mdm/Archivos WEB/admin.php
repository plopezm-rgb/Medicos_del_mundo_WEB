<?php
require_once "auth_admin.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Panel de administración</h1>
        <p>Gestión de la web</p>
    </section>

    <a href="index.php" class="boton-volver">Inicio</a>
</header>

<main>
    <section class="contenedor">

        <a href="usuarios.php" class="tarjeta">
            <h2>Gestión de usuarios</h2>
            <p>Crear, ver o eliminar usuarias</p>
        </a>

        <a href="gestionar_categorias.php" class="tarjeta">
    <h2>Gestionar categorías</h2>
    <p>Crear o eliminar categorías de la web</p>
</a>

    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>