<?php
session_start(); // Iniciamos sesión para comprobar el rol
require_once "conexion.php";
require_once "Categoria.php";

$categorias = Categoria::obtenerTodas($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Guía de Apoyo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Guía de apoyo</h1>
    </section>
    
    <?php 
    // Si la usuaria está logueada como admin o editora, aparece el botón de gestión
    if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'administradora' || $_SESSION['rol'] === 'editora')): 
    ?>
        <a href="gestionar_contenido.php" class="boton-volver">⚙️ Gestionar</a>
    <?php endif; ?>

    <?php if (!isset($_SESSION["id_usuario"])): ?>
    <a href="login.php" class="boton-header">Iniciar sesión</a>
    <?php endif; ?>

    <?php if (isset($_SESSION["id_usuario"])): ?>
    <a href="logout.php" class="boton-header">Salir</a>
    <?php endif; ?>
    
</header>

<main>
    <section class="contenedor">
        <?php foreach ($categorias as $categoria): ?>
            <a class="tarjeta" href="categoria_vista.php?id=<?php echo $categoria->getIdCategoria(); ?>">
                <section class="icono">
                    <?php echo $categoria->getIcono(); ?>
                </section>
                <h2><?php echo $categoria->getTitulo(); ?></h2>
                <p><?php echo $categoria->getDescripcion(); ?></p>
            </a>
        <?php endforeach; ?>
    </section>
    
</main>

<?php include 'footer.php'; ?>

</body>
</html>