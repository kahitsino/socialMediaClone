<?php
session_start();

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug log
$debug_log = "=== Login Attempt ===\n";
$debug_log .= "Time: " . date('Y-m-d H:i:s') . "\n";

// Database configuration
$host = 'localhost';
$dbname = 'db';
$db_username = 'root';
$db_password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    
    $debug_log .= "Login Input: $login\n";
    $debug_log .= "Password Input: [hidden]\n";

    // Check if email or username
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $debug_log .= "Using field: $field\n";
    
    $sql = "SELECT id, username, email, password FROM users WHERE $field = :login";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $debug_log .= "User Found: " . print_r($user, true) . "\n";
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            
            $debug_log .= "Session Variables Set:\n";
            $debug_log .= "user_id: " . $_SESSION['user_id'] . "\n";
            $debug_log .= "username: " . $_SESSION['username'] . "\n";
            $debug_log .= "email: " . $_SESSION['email'] . "\n";
            
            file_put_contents('../../debug.txt', $debug_log, FILE_APPEND);
            
            // Return success response
            echo json_encode(['success' => true]);
            exit();
        } else {
            $debug_log .= "Login FAILED: Invalid password\n";
            file_put_contents('debug.txt', $debug_log, FILE_APPEND);
            
            echo json_encode(['success' => false, 'error' => 'password']);
            exit();
        }
    } else {
        $debug_log .= "Login FAILED: User not found\n";
        file_put_contents('debug.txt', $debug_log, FILE_APPEND);
        
        echo json_encode(['success' => false, 'error' => 'user']);
        exit();
    }
} catch(PDOException $e) {
    $debug_log .= "Database ERROR: " . $e->getMessage() . "\n";
    file_put_contents('debug.txt', $debug_log, FILE_APPEND);
    
    echo json_encode(['success' => false, 'error' => 'database']);
    exit();
}
?>