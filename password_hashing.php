<?php
// fix_passwords.php - I-run lang ito isang beses
$host = 'localhost';
$dbname = 'db';
$db_username = 'root';
$db_password = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);

// Kunin lahat ng users
$stmt = $conn->query("SELECT id, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    // Kung hindi naka-hash ang password (length < 60)
    if (strlen($user['password']) < 60) {
        $new_hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$new_hash, $user['id']]);
        echo "Updated password for user ID: " . $user['id'] . "<br>";
    }
}
echo "Password update complete!";
?>