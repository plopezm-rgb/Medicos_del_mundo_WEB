<?php
class Usuario
{
    private $idUsuario;
    private $nombre;
    private $email;
    private $password;
    private $rol;

    public function __construct($idUsuario, $nombre, $email, $password, $rol)
    {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function getIdUsuario() { return $this->idUsuario; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRol() { return $this->rol; }

    public static function crear($conexion, $nombre, $email, $password, $idRol)
{
    $sql = "INSERT INTO usuario (nombre, email, password, id_rol)
            VALUES (:nombre, :email, :password, :id_rol)";

    $stmt = $conexion->prepare($sql);

    return $stmt->execute([
        'nombre' => $nombre,
        'email' => $email,
        'password' => $password,
        'id_rol' => $idRol
    ]);
}

public static function obtenerRoles($conexion)
{
    return $conexion->query("SELECT id_rol, nombre FROM rol")->fetchAll();
}

public static function obtenerTodosConRol($conexion)
{
    $sql = "SELECT u.id_usuario, u.nombre, u.email, r.nombre AS rol
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol";

    return $conexion->query($sql)->fetchAll();
}

public static function eliminar($conexion, $idUsuario)
{
    $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
    $stmt = $conexion->prepare($sql);

    return $stmt->execute(['id_usuario' => $idUsuario]);
}


    public static function login($conexion, $email)
    {
        $sql = "SELECT u.id_usuario, u.nombre, u.email, u.password, r.nombre AS rol
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.email = :email";

        $stmt = $conexion->prepare($sql);
        $stmt->execute(['email' => $email]);

        $fila = $stmt->fetch();

        if (!$fila) return null;

        return new Usuario(
            $fila['id_usuario'],
            $fila['nombre'],
            $fila['email'],
            $fila['password'],
            $fila['rol']
        );
    }
}
?>