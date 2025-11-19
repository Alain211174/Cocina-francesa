<?php
session_start();
if(isset($_SESSION['error'])) {
    $mostrar_error = true;
    $mensaje_error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $mostrar_error = false;
    $mensaje_error = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="media/francia.png" type="image/x-icon">
    <title>Registrarse</title>
    <script src="https://kit.fontawesome.com/fc95ca9248.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z7RB5J8FXP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Z7RB5J8FXP');
</script>

<body>


    <!-- Nav Superior -->
    <nav class="navegacion-superior">
        <div class="contenedor-navegacion">
            <div class="logo-navegacion">
                <a href="index.php">
                    <img src="media/Logo.png" alt="Logo Gastronomía Francesa">
                </a>
            </div>
            
            <div class="menu-navegacion">
                <a href="index.php" class="link">Inicio</a>
                <a href="nosotros.php" class="link">Nosotros</a>
                <a href="contacto.php" class="link">Contacto</a>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="usuario-sesion">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="cerrar_sesion.php" class="boton-login">
                        <i class="fa-solid fa-sign-out" style="color: #ffffff;"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <a href="login.php" class="boton-login">
                        <i class="fa-solid fa-user" style="color: #ffffff;"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="menu-hamburguesa">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <div class="contenedor-sesion">
        <div class="tarjeta-sesion">
            <h1>Registrarse</h1>

            <!-- Mensaje de error -->
            <?php if($mostrar_error): ?>
            <div class="mensaje-error" id="mensaje-error">
                <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
            <?php else: ?>
            <div class="mensaje-error" id="mensaje-error" style="display: none;"></div>
            <?php endif; ?>

            <form action="procesar_registro.php" method="POST" class="formulario-sesion">
                <div class="campo-formulario">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="campo-formulario">
                    <label for="carrera_uni">Carrera universitaria:</label>
                    <input type="text" id="carrera_uni" name="carrera_uni" required>
                </div>

                <div class="campo-formulario">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="campo-formulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="campo-formulario">
                    <label for="confirmar_password">Confirmar contraseña:</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" required>
                </div>

                <button type="submit" class="boton-login-form" name="registrar" value="registrar">Registrarse</button>
            </form>
            
            <p class="enlace-registro">¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión</a></p>

        </div>
    </div>

    <script>
        // Validación de contraseñas coincidentes
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmarPassword = document.getElementById('confirmar_password').value;
            const mensajeError = document.getElementById('mensaje-error');
            
            if (password !== confirmarPassword) {
                e.preventDefault();
                mensajeError.textContent = 'Las contraseñas no coinciden.';
                mensajeError.style.display = 'block';
            }
        });
    </script>
</body>
</html>