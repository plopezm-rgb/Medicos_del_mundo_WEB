<?php
require_once "conexion.php";
require_once "auth_admin.php"; // CAMBIADO: Usamos tu archivo real de autenticación

// Verificamos que solo admin o editora puedan entrar
restringirAcceso(["administradora", "editora"]);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// TODO: GUARDAR METODO EN CLASE BLOQUE
// Si se pulsa el botón de Guardar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_bloque = (int)$_POST['id_bloque'];
    
    $sql = "UPDATE bloque SET titulo = :t, subtitulo = :s, contenido = :c WHERE id_bloque = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        't' => $_POST['titulo'],
        's' => $_POST['subtitulo'],
        'c' => $_POST['contenido'],
        'id' => $id_bloque
    ]);
    
    header("Location: contenido_vista.php?id=" . $id_bloque);
    exit;
}

// Obtener los datos actuales del bloque para rellenar el formulario
$stmt = $conexion->prepare("SELECT * FROM bloque WHERE id_bloque = :id");
$stmt->execute(['id' => $id]);
$bloque = $stmt->fetch();

// Si el bloque no existe, volvemos al inicio
if (!$bloque) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Texto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Editando Contenido</h1>
        <p>Estás modificando: <strong><?php echo htmlspecialchars($bloque['titulo']); ?></strong></p>
    </section>
    <a href="contenido_vista.php?id=<?php echo $id; ?>" class="boton-volver">Cancelar</a>
</header>

<main class="login-main">
    <section class="tarjeta tarjeta-login">
        <form method="POST" class="login-form">
            <input type="hidden" name="id_bloque" value="<?php echo $bloque['id_bloque']; ?>">
            
            <section class="grupo-input">
                <label>Título del Bloque</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($bloque['titulo']); ?>" required>
            </section>

            <section class="grupo-input">
                <label>Subtítulo</label>
                <input type="text" name="subtitulo" value="<?php echo htmlspecialchars($bloque['subtitulo']); ?>">
            </section>

            <section class="grupo-input">
                <label>Contenido</label>
                <textarea name="contenido" rows="15"><?php echo htmlspecialchars($bloque['contenido']); ?></textarea>
                <p class="nota-ayuda">
                    Este formato interactivo se aplica solo en la categoría Empleo.
                    Para activarlo, escribe el contenido con:
                    <br><strong>## Sección</strong> para el tema principal y <strong>### Título</strong> para cada tarjeta.
                    <br>Ejemplo:
                    <br><em>## Tipos de contrato</em>
                    <br><em>### ¿Qué es un contrato?</em>
                    <br><em>Un contrato es ...</em>
                    <br><em>### Contrato a tiempo completo</em>
                    <br><em>Explicación del contrato a tiempo completo.</em>
                </p>
            </section>

            <section class="grupo-input">
                <button type="submit" class="boton-login" style="background-color: #28a745;">Guardar Cambios</button>
            </section>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>