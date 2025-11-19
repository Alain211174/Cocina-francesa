<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para comentar']);
    exit;
}

// Verificar datos requeridos
if (!isset($_POST['id_platillo']) || !isset($_POST['texto_comentario']) || empty(trim($_POST['texto_comentario']))) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$id_platillo = $_POST['id_platillo'];
$texto_comentario = trim($_POST['texto_comentario']);
$id_usuario = $_SESSION['user_id'];
$carrera_usuario = $_SESSION['user_carrera'];

try {
    $database = new Conexion();
    $conn = $database->getConnection();
    
    // Insertar el comentario
    $query = "INSERT INTO comentarios (texto, id_platillo, id_usuario, carrera_usuario) 
              VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$texto_comentario, $id_platillo, $id_usuario, $carrera_usuario]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentario enviado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar el comentario'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error al procesar el comentario: ' . $e->getMessage()
    ]);
}
?>