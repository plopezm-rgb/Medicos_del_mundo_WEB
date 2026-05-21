<?php
require_once "conexion.php";
require_once "Bloque.php";

$idCategoria = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$bloques = Bloque::obtenerPorCategoria($conexion, $idCategoria);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categoría</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <section class="cabecera-texto">
        <h1>Empleo</h1>
        <p>Selecciona una opción</p>
    </section>
    
</header>
<a href="index.php" class="boton-volver">← Inicio</a>

<main>
    <section class="contenedor">
        <!-- Mostrar solo los bloques que pertenecen a la categoría seleccionada -->
      <?php
      // Recorremos los bloques y mostramos solo los que corresponden a la categoría actual
    foreach ($bloques as $bloque) {

    if ($bloque->getIdCategoria() == $idCategoria) {

        // ICONO SEGÚN TÍTULO
        $icono = "fa-file-lines";

        $titulo = strtolower($bloque->getTitulo());

        /* EMPLEO */
        if (strpos($titulo, "contrato") !== false) {
        $icono = "fa-file-contract";
        } elseif (strpos($titulo, "jornada") !== false) {
        $icono = "fa-clock";
        } elseif (strpos($titulo, "empleo") !== false) {
        $icono = "fa-briefcase";
        }

        /* FORMACIÓN */
        elseif (strpos($titulo, "curso") !== false) {
            $icono = "fa-graduation-cap";
        } elseif (strpos($titulo, "formativo") !== false) {
            $icono = "fa-user-graduate";
        } elseif (strpos($titulo, "importancia") !== false) {
            $icono = "fa-lightbulb";
        }

        /* SALARIO */
        elseif (strpos($titulo, "salario") !== false) {
            $icono = "fa-euro-sign";
        } elseif (strpos($titulo, "tipos") !== false) {
            $icono = "fa-layer-group";
        } elseif (strpos($titulo, "impagos") !== false) {
            $icono = "fa-triangle-exclamation";
        } elseif (strpos($titulo, "nómina") !== false) {
            $icono = "fa-receipt";
        } elseif (strpos($titulo, "deducciones") !== false) {
            $icono = "fa-percent";
        }

        /* LUGARES DE INTERÉS */
        elseif (strpos($titulo, "sepe") !== false) {
            $icono = "fa-building";
        } elseif (strpos($titulo, "inaem") !== false) {
            $icono = "fa-map-location-dot";
        } elseif (strpos($titulo, "seguridad social") !== false) {
            $icono = "fa-id-card";
        }

        /* PIDE AYUDA */
        elseif (strpos($titulo, "ayuda") !== false) {
            $icono = "fa-hand-holding-heart";
        } elseif (strpos($titulo, "derechos") !== false) {
            $icono = "fa-scale-balanced";
        } elseif (strpos($titulo, "teléfono") !== false) {
            $icono = "fa-phone";
        }

        echo 
        "<a class='tarjeta' href='contenido_vista.php?id=" . $bloque->getIdBloque() . "'>

            <section class='icono'>
                <i class='fa-solid " . $icono . "'></i>
            </section>

            <h2>" . $bloque->getTitulo() . "</h2>
            <p>" . $bloque->getSubtitulo() . "</p>

        </a>";
    }
}
?>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>