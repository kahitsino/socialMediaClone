<?php
session_start();

// Headers para maiwasan ang caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check kung valid ang session
$loggedin = false;
if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'])) {
    // Additional validation
    if (is_numeric($_SESSION['user_id']) && !empty($_SESSION['username'])) {
        $loggedin = true;
    }
}

// Response
$response = [
    'loggedin' => $loggedin,
    'username' => $loggedin ? $_SESSION['username'] : null
];

// Set content type
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>