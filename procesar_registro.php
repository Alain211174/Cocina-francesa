<?php
session_start();
require_once 'conexion.php';

unset($_SESSION['error']);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    
    try {
        
        $nombre = trim($_POST['nombre']);
        $carrera_uni = trim($_POST['carrera_uni']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmar_password = trim($_POST['confirmar_password']);
        
        
        if(empty($nombre) || empty($carrera_uni) || empty($email) || empty($password) || empty($confirmar_password)) {
            throw new Exception('Todos los campos son obligatorios.');
        }
        
        if($password !== $confirmar_password) {
            throw new Exception('Las contraseñas no coinciden.');
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido.');
        }
        
        if(strlen($password) < 6) {
            throw new Exception('La contraseña debe tener al menos 6 caracteres.');
        }
        
        
        $database = new Conexion();
        $conn = $database->getConnection();
        
        if($conn === null) {
            throw new Exception('Error de conexión con la base de datos.');
        }
        
        
        $query_check = "SELECT id_usuario FROM Usuario WHERE email = :email";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();
        
        if($stmt_check->rowCount() > 0) {
            throw new Exception('El email ya está registrado.');
        }
        
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        
        $query = "INSERT INTO Usuario (nombre_usuario, carrera_uni, email, contrasena_hash) 
                  VALUES (:nombre, :carrera_uni, :email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':carrera_uni', $carrera_uni);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        
        if($stmt->execute()) {
            
            $_SESSION['exito'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
            header("Location: login.php");
            exit;
        } else {
            throw new Exception('Error al registrar el usuario.');
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: registro.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'Acceso no permitido.';
    header("Location: registro.php");
    exit;
}
?>