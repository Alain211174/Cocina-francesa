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
            ORDER BY id_platillo
            LIMIT 3";

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
    <title>Contacto - Digitalerias</title>
    <script src="https://kit.fontawesome.com/fc95ca9248.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/nosotros.css">
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

        <!-- Sección 1: Información del Restaurante -->
    <section id="informacion-restaurante" class="contenido-principal">
        <div class="bienvenida">
            <h1>Bienvenidos a La Rose d'Or</h1>
            <p>Un rincón del sabor francés en el corazón de México. Disfruta de nuestra cocina tradicional francesa, combinada con los sabores locales.</p>
            <p>El Restaurante La Rose d'Or nació de una pasión por la auténtica cocina francesa y el deseo de compartirla en México. Fundado en 2015 por un chef parisino y su socio mexicano, el restaurante se concibió como un puente entre dos culturas culinarias ricas y variadas. Después de años de formación en la tradición gastronómica francesa y de explorar los sabores mexicanos, decidieron abrir este pequeño rincón en el corazón de la Ciudad de México, con la misión de ofrecer una experiencia gastronómica única que celebrara la sofisticación de la cocina francesa fusionada con la frescura y la calidez de los ingredientes locales. Hoy, La Rose d'Or es un espacio donde la tradición y la innovación se encuentran, haciendo de cada plato una obra de arte.</p>
        </div>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </section>

    <!-- Sección 2: Ubicación -->
    <section id="ubicacion-restaurante" class="contenido-principal">
        <h2>Ubicación</h2>
        <div class="informacion-ubicacion">
            <div class="imagen-ubicacion">
                <img src="media/ubicacion.png" alt="Ubicación del restaurante">
            </div>
            <div class="texto-ubicacion">
                <p>Av. P.º de la Reforma 142, Juárez, Cuauhtémoc, 06600 Ciudad de México, CDMX</p>
            </div>
        </div>
    </section>


    <!-- Sección 3: Sobre mí -->
    <section id="sobre-mi" class="contenido-principal">
        <div class="informacion">
            <h2>Sobre mí</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tincidunt venenatis orci, sed sollicitudin ipsum sollicitudin nec. Nulla facilisi. Vivamus vel dolor et nisl congue euismod.</p>
            <p>Etiam et venenatis purus. Suspendisse at urna libero. Integer pharetra orci at magna fringilla, a condimentum odio convallis.</p>
        </div>
        <div class="imagen">
            <img src="media/Ficha.png">
        </div>
    </section>




</body>
<script src="js/index.js"></script>
</html>