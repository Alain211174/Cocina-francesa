<?php
session_start();
unset($_SESSION['error']);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    try {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
            
        if(empty($email) || empty($password)) {
            throw new Exception('Todos los campos son obligatorios.');
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del correo electrónico no es válido.');
        }
            
        require_once 'conexion.php';
        $database = new Conexion();
        $conn = $database->getConnection();
        
        if($conn === null) {
            throw new Exception('Error de conexión con la base de datos. Contacta al administrador.');
        }
            
        $query = "SELECT id_usuario, nombre_usuario, carrera_uni, email, contrasena_hash 
                  FROM Usuario 
                  WHERE email = :email AND activo = 1 
                  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if(!$stmt->execute()) {
            throw new Exception('Error en la consulta de la base de datos.');
        }
            
        if($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($password, $usuario['contrasena_hash'])) {
                $_SESSION['user_id'] = $usuario['id_usuario'];
                $_SESSION['user_name'] = $usuario['nombre_usuario'];
                $_SESSION['user_carrera'] = $usuario['carrera_uni'];
                $_SESSION['user_email'] = $usuario['email'];
                $_SESSION['logged_in'] = true;
                
                // Redirigir a la página principal después del login exitoso
                header("Location: index.php");
                exit;
                
            } else {
                throw new Exception('Contraseña incorrecta.');
            }
        } else {
            throw new Exception('Usuario no encontrado o cuenta inactiva. Verifica tu email o regístrate.');
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'Acceso no permitido.';
    header("Location: login.php");
    exit;
}
?>