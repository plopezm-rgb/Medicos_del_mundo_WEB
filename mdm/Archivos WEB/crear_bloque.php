<?php
require_once "conexion.php";
require_once "auth_admin.php";
require_once "Bloque.php";
require_once "Categoria.php";

restringirAcceso(["administradora", "editora"]);

$categorias = Categoria::obtenerTodas($conexion);
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST['titulo'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;

    if ($titulo === '' || $id_categoria <= 0) {
        $error = 'Debes indicar un título y una categoría válidos.';
    } else {
        if (Bloque::guardar($conexion, $titulo, $subtitulo, $contenido, $id_categoria)) {
            header("Location: gestionar_contenido.php");
            exit;
        }
        $error = 'No se pudo guardar el bloque. Intenta de nuevo.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Bloque</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    <section class="cabecera-texto">
        <h1>Crear nuevo bloque</h1>
        <p>Introduce el contenido que quieres añadir a la web.</p>
    </section>
    <a href="gestionar_contenido.php" class="boton-volver">Cancelar</a>
</header>

<main class="login-main">
    <section class="tarjeta tarjeta-login">
        <?php if ($error): ?>
            <div class="error-mensaje"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <section class="grupo-input">
                <label>Título del Bloque</label>
                <input type="text" name="titulo" value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>" required>
            </section>

            <section class="grupo-input">
                <label>Subtítulo</label>
                <input type="text" name="subtitulo" value="<?php echo isset($_POST['subtitulo']) ? htmlspecialchars($_POST['subtitulo']) : ''; ?>">
            </section>

            <section class="grupo-input">
                <label>Categoría</label>
                <select name="id_categoria" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria->getIdCategoria(); ?>"<?php echo (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $categoria->getIdCategoria()) ? ' selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria->getTitulo()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </section>

            <section class="grupo-input">
                <label>Contenido</label>
                <textarea name="contenido" rows="15"><?php echo isset($_POST['contenido']) ? htmlspecialchars($_POST['contenido']) : ''; ?></textarea>
            </section>

            <section class="grupo-input">
                <button type="submit" class="boton-login" style="background-color: #007bff;">Crear Bloque</button>
            </section>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
