<?php
$host = 'localhost'; // Change if needed
$db_name = 'myapi';
$username = 'root';  // Your DB username
$password = '';      // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
