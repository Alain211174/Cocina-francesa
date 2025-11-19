<?php
require_once 'conexion.php';

header('Content-Type: application/json');

if (!isset($_GET['id_platillo'])) {
    echo json_encode(['success' => false, 'message' => 'ID de platillo no proporcionado']);
    exit;
}

$id_platillo = $_GET['id_platillo'];

try {
    $database = new Conexion();
    $conn = $database->getConnection();
    
    $query = "SELECT c.texto, c.fecha_creacion, u.nombre_usuario, c.carrera_usuario 
              FROM comentarios c 
              JOIN usuario u ON c.id_usuario = u.id_usuario 
              WHERE c.id_platillo = ? 
              ORDER BY c.fecha_creacion DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_platillo]);
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comentarios' => $comentarios
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error al cargar comentarios: ' . $e->getMessage()
    ]);
}
?>