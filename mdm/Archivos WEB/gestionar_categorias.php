<?php
require_once "auth_editora.php";
require_once "conexion.php";
require_once "Categoria.php";
require_once "Bloque.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $icono = trim($_POST["icono"]);

    // 1. Crear categoría y obtener ID
    $idCategoria = Categoria::crear($conexion, $titulo, $descripcion, $icono);

    // 2. Crear primer bloque dentro de esa categoría
    Bloque::crear(
        $conexion,
        $_POST["titulo_bloque"],
        $_POST["subtitulo_bloque"],
        $_POST["contenido_bloque"],
        1,
        $idCategoria
    );

    $mensaje = "Categoría y bloque creados correctamente.";
}

if (isset($_GET["borrar"])) {
    $idCategoria = (int) $_GET["borrar"];

    Categoria::eliminar($conexion, $idCategoria);

    header("Location: gestionar_categorias.php");
    exit;
}

$categorias = Categoria::obtenerTodas($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar categorías</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <img src="img/logomdm.png" alt="Logo" class="logo">

    <section class="cabecera-texto">
        <h1>Gestionar categorías</h1>
        <p>Crear o eliminar categorías</p>
    </section>

    <a href="admin.php" class="boton-volver">Volver</a>
</header>

<main class="panel-admin">

    <section class="panel-card">
        <h2>Crear categoría</h2>

        <?php if ($mensaje != ""): ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <section class="grupo-input">
                <label>Título</label>
                <input type="text" name="titulo" required>
            </section>

            <section class="grupo-input">
                <label>Descripción</label>
                <input type="text" name="descripcion" required>
            </section>

            <section class="grupo-input">
                <label>Icono</label>
                <input type="text" name="icono" placeholder="Ej: 💼">
            </section>

            <button type="submit" class="boton-login">Crear categoría</button>

            <h3>Primer bloque</h3>

<section class="grupo-input">
    <label>Título del bloque</label>
    <input type="text" name="titulo_bloque" required>
</section>

<section class="grupo-input">
    <label>Subtítulo del bloque</label>
    <input type="text" name="subtitulo_bloque" required>
</section>

<section class="grupo-input">
    <label>Contenido</label>
    <textarea name="contenido_bloque" rows="6" required></textarea>
</section>
        </form>
    </section>

    <section class="panel-card">
        <h2>Categorías actuales</h2>

        <section class="lista-contenidos">
            <?php foreach ($categorias as $categoria): ?>
                <article class="contenido-item">
                    <section>
                        <span class="etiqueta"><?php echo htmlspecialchars($categoria->getIcono()); ?></span>
                        <h3><?php echo htmlspecialchars($categoria->getTitulo()); ?></h3>
                        <p><?php echo htmlspecialchars($categoria->getDescripcion()); ?></p>
                    </section>

                    <section class="acciones">
    
    <a href="editar_categoria.php?id=<?php echo $categoria->getIdCategoria(); ?>" class="boton-editar">
        Editar
    </a>

    <a href="gestionar_categorias.php?borrar=<?php echo $categoria->getIdCategoria(); ?>" class="boton-borrar">
        Borrar
    </a>
</section>
                </article>
            <?php endforeach; ?>
        </section>
    </section>

</main>

</body>
</html>