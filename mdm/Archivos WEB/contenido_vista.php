<?php
session_start(); 
require_once "conexion.php";
require_once "Bloque.php";

$idBloque = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$bloque = Bloque::obtenerPorId($conexion, $idBloque);

if ($bloque) {
    $titulo = $bloque->getTitulo();
    $subtitulo = $bloque->getSubtitulo();
    $contenido = $bloque->getContenido();
    $idCategoria = $bloque->getIdCategoria();
} else {
    $titulo = "No encontrado";
    $subtitulo = "";
    $contenido = "No existe este contenido";
    $idCategoria = 1;
}

function parseContenidoInteractivo($texto) {
    $texto = trim($texto);
    $lineas = preg_split('/\r\n|\r|\n/', $texto);
    $secciones = [];
    $currentSectionIndex = null;
    $currentCardIndex = null;

    foreach ($lineas as $linea) {
        $trim = trim($linea);
        if ($trim === '') {
            continue;
        }

        if (preg_match('/^##\s*(.+)$/', $trim, $matches)) {
            $secciones[] = [
                'title' => $matches[1],
                'description' => '',
                'cards' => []
            ];
            $currentSectionIndex = count($secciones) - 1;
            $currentCardIndex = null;
            continue;
        }

        if (preg_match('/^###\s*(.+)$/', $trim, $matches)) {
            if ($currentSectionIndex === null) {
                $secciones[] = [
                    'title' => '',
                    'description' => '',
                    'cards' => []
                ];
                $currentSectionIndex = count($secciones) - 1;
            }
            $secciones[$currentSectionIndex]['cards'][] = [
                'title' => $matches[1],
                'description' => ''
            ];
            $currentCardIndex = count($secciones[$currentSectionIndex]['cards']) - 1;
            continue;
        }

        if ($currentCardIndex !== null && $currentSectionIndex !== null) {
            $secciones[$currentSectionIndex]['cards'][$currentCardIndex]['description'] .= ($secciones[$currentSectionIndex]['cards'][$currentCardIndex]['description'] ? "\n" : "") . $trim;
        } elseif ($currentSectionIndex !== null) {
            $secciones[$currentSectionIndex]['description'] .= ($secciones[$currentSectionIndex]['description'] ? "\n" : "") . $trim;
        }
    }

    // Fallback parser para contenido plano de empleo
    if (empty($secciones) || count(array_filter($secciones, fn($s) => count($s['cards'])) ) === 0) {
        $paragraphs = preg_split('/\n\s*\n/', $texto);
        $cards = [];
        $intro = '';
        $currentTitle = null;
        $currentDescription = '';

        foreach ($paragraphs as $parrafo) {
            $parrafo = trim($parrafo);
            if ($parrafo === '') {
                continue;
            }

            $lineaSimple = strtok($parrafo, "\n");
            $esTitulo = false;

            if (preg_match('/^##\s*(.+)$/', $lineaSimple) || preg_match('/^###\s*(.+)$/', $lineaSimple)) {
                $esTitulo = true;
            } elseif (strlen($lineaSimple) < 120 && !preg_match('/[.?!:]$/u', $lineaSimple)) {
                $esTitulo = true;
            }

            if ($esTitulo && $currentTitle !== null) {
                $cards[] = [
                    'title' => $currentTitle,
                    'description' => trim($currentDescription)
                ];
                $currentDescription = '';
            }

            if ($esTitulo) {
                $currentTitle = $lineaSimple;
                $currentDescription = trim(substr($parrafo, strlen($lineaSimple)));
            } elseif ($currentTitle !== null) {
                $currentDescription .= ($currentDescription ? "\n\n" : "") . $parrafo;
            } else {
                $intro .= ($intro ? "\n\n" : "") . $parrafo;
            }
        }

        if ($currentTitle !== null) {
            $cards[] = [
                'title' => $currentTitle,
                'description' => trim($currentDescription)
            ];
        }

        if (!empty($cards)) {
            return [[
                'title' => '',
                'description' => trim($intro),
                'cards' => $cards
            ]];
        }
    }

    return $secciones;
}

$contenidoInteractivo = parseContenidoInteractivo($contenido);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
     <a href="categoria_vista.php?id=<?php echo $idCategoria; ?>" class="boton-volver">← Volver</a>
     
     <?php 
     // Botón de edición rápida en la cabecera
     if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'administradora' || $_SESSION['rol'] === 'editora')): 
     ?>
        <a href="editar_bloque.php?id=<?php echo $idBloque; ?>" class="boton-volver">✏️ Editar</a>
     <?php endif; ?>
</header>

<main>
    <section class="detalle">
        <h1><?php echo $titulo; ?></h1>
        <h3><?php echo $subtitulo; ?></h3>

        <?php if ($idCategoria === 1 && !empty($contenidoInteractivo) && count(array_filter($contenidoInteractivo, function($s) { return count($s['cards']) > 0; }))): ?>
            <section class="contenido-interactivo">
                <?php if (count($contenidoInteractivo) === 1 && empty($contenidoInteractivo[0]['title'])): ?>
                    <?php if (!empty($contenidoInteractivo[0]['description'])): ?>
                        <section class="texto-seccion">
                            <?php echo nl2br(htmlspecialchars($contenidoInteractivo[0]['description'])); ?>
                    </section>
                    <?php endif; ?>
                    <section class="tarjetas-interior">
                        <?php foreach ($contenidoInteractivo[0]['cards'] as $card): ?>
                            <details class="tarjeta tarjeta-interactiva">
                                <summary class="tarjeta-titulo"><?php echo htmlspecialchars($card['title']); ?></summary>
                                <section class="descripcion-card">
                                    <?php echo nl2br(htmlspecialchars($card['description'])); ?>
                                </section>
                            </details>
                        <?php endforeach; ?>
                    </section>
                <?php else: ?>
                    <?php foreach ($contenidoInteractivo as $index => $seccion): ?>
                        <details class="seccion-detalle">
                            <summary class="seccion-resumen"><?php echo htmlspecialchars($seccion['title'] ?: 'Sección ' . ($index + 1)); ?></summary>

                            <?php if (!empty($seccion['description'])): ?>
                                <section class="texto-seccion">
                                    <?php echo nl2br(htmlspecialchars($seccion['description'])); ?>
                                </section>
                            <?php endif; ?>

                            <section class="tarjetas-interior">
                                <?php foreach ($seccion['cards'] as $card): ?>
                                    <details class="tarjeta tarjeta-interactiva">
                                        <summary class="tarjeta-titulo"><?php echo htmlspecialchars($card['title']); ?></summary>
                                        <section class="descripcion-card">
                                            <?php echo nl2br(htmlspecialchars($card['description'])); ?>
                                        </section>
                                    </details>
                                <?php endforeach; ?>
                            </section>
                        </details>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <p><?php echo nl2br(htmlspecialchars($contenido)); ?></p>
        <?php endif; ?>

    </section>
</main>


<?php include 'footer.php'; ?>
</body>
</html>