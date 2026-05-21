<?php
class Bloque
{
    private $idBloque;
    private $titulo;
    private $subtitulo;
    private $contenido;
    private $orden;
    private $fechaActualizacion;
    private $idCategoria;

    public function __construct($idBloque, $titulo, $subtitulo, $contenido, $orden, $fechaActualizacion, $idCategoria)
    {
        $this->idBloque = $idBloque;
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->contenido = $contenido;
        $this->orden = $orden;
        $this->fechaActualizacion = $fechaActualizacion;
        $this->idCategoria = $idCategoria;
    }

    public function getIdBloque()
    {
        return $this->idBloque;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getSubtitulo()
    {
        return $this->subtitulo;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function getOrden()
    {
        return $this->orden;
    }

    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setSubtitulo($subtitulo)
    {
        $this->subtitulo = $subtitulo;
    }

    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;
    }

    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
    }

    public static function obtenerPorCategoria($conexion, $idCategoria){
    $bloques = [];

    $sql = "SELECT id_bloque, titulo, subtitulo, contenido, orden, fecha_actualizacion, id_categoria
            FROM bloque
            WHERE id_categoria = :id_categoria
            ORDER BY orden";

    $stmt = $conexion->prepare($sql);
    $stmt->execute(['id_categoria' => $idCategoria]);

    while ($fila = $stmt->fetch()) {
        $bloques[] = new Bloque(
            $fila['id_bloque'],
            $fila['titulo'],
            $fila['subtitulo'],
            $fila['contenido'],
            $fila['orden'],
            $fila['fecha_actualizacion'],
            $fila['id_categoria']
        );
    }

    return $bloques;
}

public static function obtenerPorId($conexion, $idBloque)
{
    $sql = "SELECT * FROM bloque WHERE id_bloque = :id_bloque";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['id_bloque' => $idBloque]);

    $fila = $stmt->fetch();

    if (!$fila) return null;

    return new Bloque(
        $fila['id_bloque'],
        $fila['titulo'],
        $fila['subtitulo'],
        $fila['contenido'],
        $fila['orden'],
        $fila['fecha_actualizacion'],
        $fila['id_categoria']
    );
}

// Añadir dentro de la clase Bloque en Bloque.php

public static function guardar($conexion, $titulo, $subtitulo, $contenido, $id_categoria) {
    $sql = "INSERT INTO bloque (titulo, subtitulo, contenido, id_categoria, orden) 
            VALUES (:titulo, :subtitulo, :contenido, :id_categoria, 0)";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        'titulo' => $titulo,
        'subtitulo' => $subtitulo,
        'contenido' => $contenido,
        'id_categoria' => $id_categoria
    ]);
}

public static function actualizar($conexion, $id_bloque, $titulo, $subtitulo, $contenido) {
    $sql = "UPDATE bloque SET titulo = :titulo, subtitulo = :subtitulo, contenido = :contenido 
            WHERE id_bloque = :id_bloque";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([
        'titulo' => $titulo,
        'subtitulo' => $subtitulo,
        'contenido' => $contenido,
        'id_bloque' => $id_bloque
    ]);
}

public static function eliminar($conexion, $id_bloque) {
    $sql = "DELETE FROM bloque WHERE id_bloque = :id_bloque";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute(['id_bloque' => $id_bloque]);
}
public static function crear($conexion, $titulo, $subtitulo, $contenido, $orden, $idCategoria)
{
    $sql = "INSERT INTO bloque (titulo, subtitulo, contenido, orden, fecha_actualizacion, id_categoria)
            VALUES (:titulo, :subtitulo, :contenido, :orden, CURRENT_DATE, :id_categoria)";

    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        "titulo" => $titulo,
        "subtitulo" => $subtitulo,
        "contenido" => $contenido,
        "orden" => $orden,
        "id_categoria" => $idCategoria
    ]);
}
}
