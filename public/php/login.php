<?php
session_start();
header('Content-Type: application/json'); // Important!

// Database connection
$conn = new mysqli('localhost', 'root', '', 'db');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Cannot connect to database']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $conn->real_escape_string(trim($_POST['login']));
    $password = trim($_POST['password']);

    // Simple validation
    if (empty($login) || empty($password)) {
        die(json_encode(['status' => 'error', 'message' => 'Pakilagay ang username at password!']));
    }

    // Sample check - palitan mo to sa actual database query
    if ($login === "johnson" && $password === "pangetako") {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $login;
        echo json_encode(['status' => 'success', 'redirect' => 'home.html']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Mali ang username o password!']);
        exit();
    }
}

$conn->close();
?>