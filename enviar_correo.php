<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aqui se obtienen los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $mensaje = $_POST['mensaje'];

    // Dirección de correo y asunto
    $destino = "alainacosta64@gmail.com";
    $asunto = "Nuevo mensaje";

    // Cuerpo del correo
    $contenido .= "Nombre: " . $nombre . "\n";
    $contenido .= "Correo: " . $correo . "\n";
    $contenido .= "Teléfono: " . $telefono . "\n";
    $contenido .= "Mensaje: \n" . $mensaje . "\n";

    // Cabeceras del correo
    $cabeceras = "From: " . $correo . "\r\n";
    $cabeceras .= "Reply-To: " . $correo . "\r\n";

    // Enviar el correo
    if (mail($destino, $asunto, $contenido, $cabeceras)) {
        echo "Mensaje enviado correctamente";
    } else {
        echo "Hubo un error al enviar el mensaje. Por favor, intenta nuevamente.";
    }
}
?>
