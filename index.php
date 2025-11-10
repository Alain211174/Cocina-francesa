<?php
session_start();
require_once 'conexion.php';

// Obtener platillos de la base de datos
try {
    $database = new Conexion();
    $conn = $database->getConnection();
    
    $query = "SELECT id_platillo, nombre_platillo, descripcion_corta, descripcion, tipo, ingredientes, video, imagen 
            FROM platillos 
            WHERE activo = 1 
            ORDER BY id_platillo";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $platillos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $platillos = [];
    $error = "Error al cargar los platillos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastronomía Francesa</title>
    <script src="https://kit.fontawesome.com/fc95ca9248.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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

    <!-- Banner -->
    <div class="banner-principal">
    </div>

    <div class="bienvenida">
        <h1>¡Explora la Gastronomía Francesa!</h1>
    </div>

    <!-- Main Content -->
    <main class="contenido-principal">
        <div class="contenedor-platillos">
            <h2>Platillos Destacados</h2>
            
            <?php if(isset($error)): ?>
                <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="grid-platillos">
                <?php if(empty($platillos)): ?>
                    <div class="mensaje-vacio">No hay platillos disponibles en este momento.</div>
                <?php else: ?>
                    <?php foreach($platillos as $platillo): ?>
                        <div class="tarjeta-platillo" data-platillo="<?php echo $platillo['id_platillo']; ?>">
                            <div class="imagen-platillo">
                                <?php if (!empty($platillo['imagen'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($platillo['imagen']); ?>" 
                                        alt="<?php echo htmlspecialchars($platillo['nombre_platillo']); ?>">
                                <?php else: ?>
                                    <img src="media/placeholder.jpg" 
                                        alt="<?php echo htmlspecialchars($platillo['nombre_platillo']); ?>">
                                <?php endif; ?>

                            </div>
                            <div class="contenido-platillo">
                                <h3 class="titulo-platillo"><?php echo htmlspecialchars($platillo['nombre_platillo']); ?></h3>
                                <p class="descripcion-platillo"><?php echo htmlspecialchars($platillo['descripcion_corta']); ?></p>
                                <button class="boton-ver-detalles" 
                                        data-platillo="<?php echo $platillo['id_platillo']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($platillo['nombre_platillo']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($platillo['descripcion']); ?>"
                                        data-ingredientes="<?php echo htmlspecialchars($platillo['ingredientes']); ?>"
                                        data-tipo="<?php echo htmlspecialchars($platillo['tipo']); ?>"
                                        data-imagen="<?php echo !empty($platillo['imagen']) ? 'data:image/jpeg;base64,' . base64_encode($platillo['imagen']) : 'media/placeholder.jpg'; ?>"> 
                                    Ver detalles
                                </button>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal para visualizar contenido -->
        <div class="modal" id="modal-platillo">
            <div class="contenido-modal">
                <button class="boton-cerrar-modal">X</button>
                
                <div class="cuerpo-modal">
                    <!-- Información del platillo -->
                    <div class="informacion-modal">
                        <h2 class="titulo-modal" id="titulo-modal"></h2>
                        <div class="detalle-informacion">
                            <p><strong>Ingredientes:</strong> <span class="ingredientes-modal" id="ingredientes-modal"></span></p>
                            <p><strong>Tipo:</strong> <span class="tipo-modal" id="tipo-modal"></span></p>
                        </div>
                        <div class="descripcion-modal">
                            <h3>Descripción</h3>
                            <p class="texto-descripcion-modal" id="descripcion-modal"></p>
                        </div>
                    </div>
                    
                    <!-- Contenido multimedia -->
                    <div class="multimedia-modal">
                        <div class="contenedor-imagen-modal">
                            <img class="imagen-modal" id="imagen-modal" src="" alt="Imagen del platillo">
                            <div class="sin-imagen" id="sin-imagen" style="display: none;">
                                <p>No hay imagen disponible para este platillo</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de comentarios -->
                <div class="seccion-comentarios">
                    <h3>Comentarios</h3>
                    <div class="contenedor-lista-comentarios">
                        <div class="lista-comentarios" id="lista-comentarios">
                            <!-- Los comentarios se cargarán dinámicamente -->
                        </div>
                    </div>
                    
                    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <form class="formulario-comentario" id="formulario-comentario" method="POST">
                            <h4>Agregar comentario</h4>
                            <input type="hidden" name="id_platillo" id="id-platillo-comentario">
                            <div class="campo-comentario">
                                <textarea id="texto-comentario" name="texto_comentario" placeholder="Escribe tu comentario..." rows="4" required></textarea>
                            </div>
                            <button type="submit" class="boton-enviar-comentario">Enviar comentario</button>
                            <div class="estado-sesion">
                                <p class="usuario-activo">
                                    Comentando como: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                                    (<?php echo htmlspecialchars($_SESSION['user_carrera']); ?>)
                                </p>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="mensaje-inicio-sesion-comentarios">
                            <p>
                                <i class="fa-solid fa-info-circle"></i>
                                Debes <a href="login.php">iniciar sesión</a> para poder comentar
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <aside id="foot_izq">
            <div id="redes">
                <h3>Siguenos en:</h3>
                <ul id="sociales">
                    <li><a href="https://wwww.facebook"><img src="media/fac.png" width="25px" height="25px"></a></li>
                    <li><a href="https://wwww.twitter"><img src="media/twi.png" width="25px" height="25px"></a></li>
                    <li><a href="https://wwww.youtube"><img src="media/you.png" width="25px" height="25px" alt=""></a></li>
                    <li><a href="https://wwww.linkedin"><img src="media/lin.png" width="25px" height="25px" alt=""></a></li>
                    <li><a href="https://wwww.instagram"><img src="media/ins.png" width="25px" height="25px" alt=""></a></li>
                </ul>
            </div>
        </aside>
        <div id="qr">
            <img src="media/qr.png" > 
        </div>
        <aside id="foot_der">
        <ul>
            <strong><h3>Contactanos</h3></strong>
            <li>Contacto: <br> Edgar Alain Acosta / alainacosta64@gmail.com</li>
            <li>Repositorio: <br>https://github.com/AlainAcosta03</li><br>
            <li>UACJ - IADA</li>
        </ul>
        </aside>
    </footer>

<script src="js/index.js"></script>
</body>
</html>