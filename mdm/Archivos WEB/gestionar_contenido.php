<?php
require_once "conexion.php";
require_once "auth_admin.php";

restringirAcceso(["administradora", "editora"]);

// CORRECCIÓN: El parámetro en el enlace era 'borrar', así que lo igualamos aquí
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $sql = "DELETE FROM bloque WHERE id_bloque = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['id' => $id]);
    header("Location: gestionar_contenido.php");
    exit;
}

// La consulta ya trae 'cat_nombre', la usaremos abajo
$stmt = $conexion->query("SELECT b.*, c.titulo as cat_nombre FROM bloque b JOIN categoria c ON b.id_categoria = c.id_categoria ORDER BY c.titulo ASC");
$bloques = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Contenido</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Gestión de Contenidos</h1>
    </section>
    <a href="index.php" class="boton-volver">Volver</a>
</header>

<main class="panel-admin">
    <section class="panel-card">
        <h2>Panel de administración</h2>
        <p>Accede a la gestión general de la aplicación.</p>

        <section class="acciones-panel">
            <a href="usuarios.php" class="boton-principal">Gestionar usuarios</a>
            <a href="admin.php" class="boton-secundario">Volver al panel</a>
        </section>
    </section>
    
    <section class="panel-card">
        <h2>Gestión de contenidos</h2>
        <p>Añade, modifica o elimina los bloques de contenido de la web.</p>
        <section class="acciones-panel">
            <a href="crear_bloque.php" class="boton-principal">Añadir bloque</a>
        </section>

        <section class="lista-contenidos">
            <?php foreach ($bloques as $bloque): ?>
                <article class="contenido-item">
                    <section>
                        <span class="etiqueta"><?php echo htmlspecialchars($bloque["cat_nombre"]); ?></span>
                        <h3><?php echo htmlspecialchars($bloque["titulo"]); ?></h3>
                    </section>

                    <section class="acciones">
                        <a href="editar_bloque.php?id=<?php echo $bloque["id_bloque"]; ?>" class="boton-editar">Editar</a>
                        
                        <a href="gestionar_contenido.php?eliminar=<?php echo $bloque["id_bloque"]; ?>" 
                           class="boton-borrar" 
                           onclick="return confirm('¿Estás segura de que quieres eliminar este contenido?')">Borrar</a>
                    </section>
                </article>
            <?php endforeach; ?>
        </section>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>