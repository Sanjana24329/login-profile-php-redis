<?php
$host = "localhost";
$db = "internship_db"; // the database you created
$user = "root";         // default XAMPP username
$pass = "";             // default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
