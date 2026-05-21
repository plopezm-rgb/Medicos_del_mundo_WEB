<?php
class Categoria
{
    private $idCategoria;
    private $titulo;
    private $descripcion;
    private $icono;
    private $fechaActualizacion;

    public function __construct($idCategoria, $titulo, $descripcion, $icono, $fechaActualizacion)
    {
        $this->idCategoria = $idCategoria;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->icono = $icono;
        $this->fechaActualizacion = $fechaActualizacion;
    }

    public function getIdCategoria() {
        return $this->idCategoria; 
    }
    public function getTitulo() { 
        return $this->titulo; 
    }
    public function getDescripcion() { 
        return $this->descripcion;
    }
    public function getIcono() {
         return $this->icono; 
    }
    public function getFechaActualizacion() { 
        return $this->fechaActualizacion; 
    }
    /**
     * Obtiene todas las categorías principales (sin madre) de la base de datos
     * @param PDO $conexion Conexión a la base de datos
     * @return Categoria[] Array de objetos Categoria
     * 
     */
    public static function obtenerTodas($conexion)
    {
        $categorias = [];

        $sql = "SELECT id_categoria, titulo, descripcion, icono, fecha_actualizacion
                FROM categoria
                WHERE id_madre IS NULL OR id_madre = 0
                ORDER BY id_categoria";

        $stmt = $conexion->query($sql);

        while ($fila = $stmt->fetch()) {
            $categorias[] = new Categoria(
                $fila['id_categoria'],
                $fila['titulo'],
                $fila['descripcion'],
                $fila['icono'],
                $fila['fecha_actualizacion']
            );
        }

        return $categorias;
    }
    
    public static function crear($conexion, $titulo, $descripcion, $icono)
{
    $sql = "INSERT INTO categoria (titulo, descripcion, icono, fecha_actualizacion)
            VALUES (:titulo, :descripcion, :icono, CURRENT_DATE)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        "titulo" => $titulo,
        "descripcion" => $descripcion,
        "icono" => $icono
    ]);

    return $conexion->lastInsertId();
}

public static function eliminar($conexion, $idCategoria)
{
    $sql = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        "id_categoria" => $idCategoria
    ]);
}
public static function obtenerPorId($conexion, $idCategoria)
{
    $sql = "SELECT * FROM categoria WHERE id_categoria = :id_categoria";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(["id_categoria" => $idCategoria]);

    return $stmt->fetch();
}

public static function actualizar($conexion, $idCategoria, $titulo, $descripcion, $icono)
{
    $sql = "UPDATE categoria
            SET titulo = :titulo,
                descripcion = :descripcion,
                icono = :icono,
                fecha_actualizacion = CURRENT_DATE
            WHERE id_categoria = :id_categoria";

    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        "titulo" => $titulo,
        "descripcion" => $descripcion,
        "icono" => $icono,
        "id_categoria" => $idCategoria
    ]);
}
}
?>
