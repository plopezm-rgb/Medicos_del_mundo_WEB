<?php
session_start();
require_once "conexion.php";
require_once "Usuario.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $usuario = Usuario::login($conexion, $email);

    if ($usuario && password_verify($password, $usuario->getPassword())) {
        $_SESSION["id_usuario"] = $usuario->getIdUsuario();
        $_SESSION["nombre"] = $usuario->getNombre();
        $_SESSION["email"] = $usuario->getEmail();
        $_SESSION["rol"] = $usuario->getRol();

        header("Location: index.php");
        exit;
    }

    $error = "Correo o contraseña incorrectos.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="estilos.css?v=2">
</head>
<body>

<header class="header">
    <a href="index.php">
        <img src="img/logomdm.png" alt="Logo" class="logo">
    </a>
    
    <h1>Acceso a la Guía</h1>

    <a href="index.php" class="boton-header">Inicio</a>
</header>

<main class="login-main">
    <section class="tarjeta tarjeta-login">
        <h2>Bienvenida</h2>

        <?php if (!empty($error)): ?>
            <p class="error-alerta"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <section class="grupo-input">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </section>

            <section class="grupo-input">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </section>

            <button type="submit" class="boton-login">Entrar</button>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>