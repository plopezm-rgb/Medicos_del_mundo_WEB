<?php
require_once "auth_editora.php";
require_once "conexion.php";
require_once "Categoria.php";

$idCategoria = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idCategoria = (int) $_POST["id_categoria"];
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $icono = trim($_POST["icono"]);

    Categoria::actualizar($conexion, $idCategoria, $titulo, $descripcion, $icono);

    header("Location: gestionar_categorias.php");
    exit;
}

$categoria = Categoria::obtenerPorId($conexion, $idCategoria);

if (!$categoria) {
    die("Categoría no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar categoría</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <img src="img/logomdm.png" alt="Logo" class="logo">

    <section class="cabecera-texto">
        <h1>Editar categoría</h1>
        <p>Modifica los datos</p>
    </section>

    <a href="gestionar_categorias.php" class="boton-volver">Volver</a>
</header>

<main class="panel-admin">
    <section class="panel-card">
        <form method="POST" class="login-form">
            <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">

            <section class="grupo-input">
                <label>Título</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($categoria['titulo']); ?>" required>
            </section>

            <section class="grupo-input">
                <label>Descripción</label>
                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($categoria['descripcion']); ?>" required>
            </section>

            <section class="grupo-input">
                <label>Icono</label>
                <input type="text" name="icono" value="<?php echo htmlspecialchars($categoria['icono']); ?>">
            </section>

            <button type="submit" class="boton-login">Guardar cambios</button>
        </form>
    </section>
</main>

</body>
</html>