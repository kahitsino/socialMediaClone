<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'db';
$db_username = 'root';
$db_password = '';

// Debugging - alisin to pag working na
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kunin ang form data
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Debug - tingnan ang received data
    file_put_contents('debug.txt', "Login attempt: $login\n", FILE_APPEND);

    // Check kung email o username
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
    $sql = "SELECT * FROM users WHERE $field = :login";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug - tingnan ang nakuha sa database
        file_put_contents('debug.txt', "User found: ".print_r($user, true)."\n", FILE_APPEND);
        
        // I-verify ang password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            // Debug - successful login
            file_put_contents('debug.txt', "Login successful for user ID: ".$user['id']."\n", FILE_APPEND);
            
            // Redirect to home page
            header('Location: ../home.html');
            exit();
        } else {
            // Debug - password mismatch
            file_put_contents('debug.txt', "Password verification failed\n", FILE_APPEND);
            
            // Invalid password
            header('Location: ../index.html?error=invalid_password');
            exit();
        }
    } else {
        // Debug - user not found
        file_put_contents('debug.txt', "User not found\n", FILE_APPEND);
        
        // User not found
        header('Location: ../index.html?error=user_not_found');
        exit();
    }
} catch(PDOException $e) {
    // Debug - database error
    file_put_contents('debug.txt', "Database error: ".$e->getMessage()."\n", FILE_APPEND);
    
    // Database error
    header('Location: ../index.html?error=database_error');
    exit();
}
?>