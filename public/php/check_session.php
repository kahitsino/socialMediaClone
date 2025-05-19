<?php
session_start();

// Strict session validation
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$response = [
    'loggedin' => false,
    'username' => null,
    'user_id' => null
];

if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'])) {
    if (is_numeric($_SESSION['user_id']) && 
        !empty(trim($_SESSION['username'])) &&
        filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)) {
        
        $response = [
            'loggedin' => true,
            'username' => htmlspecialchars($_SESSION['username']),
            'user_id' => (int)$_SESSION['user_id']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?>