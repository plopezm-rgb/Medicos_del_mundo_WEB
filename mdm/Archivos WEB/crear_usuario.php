<?php
require_once "auth_admin.php";
require_once "conexion.php";
require_once "Usuario.php";
require_once "auth_admin.php";
restringirAcceso(["administradora"]);

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $idRol = (int) $_POST["id_rol"];

    $creado = Usuario::crear($conexion, $nombre, $email, $password, $idRol);

    if ($creado) {
        $mensaje = "Usuaria creada correctamente.";
    } else {
        $error = "No se pudo crear la usuaria.";
    }
}

$roles = Usuario::obtenerRoles($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuaria</title>
    <link rel="stylesheet" href="estilos.css?v=2">
</head>
<body>
<header class="header">
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <h1>Crear usuaria</h1>
    <a href="usuarios.php" class="boton-volver">← Volver</a>
</header>

<main class="login-main">
    <section class="tarjeta tarjeta-login">
        <?php if ($mensaje): ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <section class="grupo-input">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </section>

            <section class="grupo-input">
                <label>Email</label>
                <input type="email" name="email" required>
            </section>

            <section class="grupo-input">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </section>

            <section class="grupo-input">
                <label>Rol</label>
                <select name="id_rol" required>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>">
                            <?php echo htmlspecialchars($rol['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </section>

            <button type="submit" class="boton-login">Crear usuaria</button>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>