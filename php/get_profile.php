<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require __DIR__ . '/../redis/session.php';

// DB connection
$host = "localhost";
$dbname = "internship_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get POST data
$email = $_POST['email'] ?? '';
$token = $_POST['session_token'] ?? '';

if (empty($email) || empty($token)) {
    echo json_encode(['success' => false, 'message' => 'Email or session token missing']);
    exit;
}

// Verify session in Redis
$storedEmail = getSession($token);
if ($storedEmail !== $email) {
    echo json_encode(['success' => false, 'message' => 'Invalid session']);
    exit;
}

// Fetch user info
$stmt = $conn->prepare("SELECT name, age, dob, contact FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $age, $dob, $contact);
if ($stmt->fetch()) {
    echo json_encode([
        'success' => true,
        'name' => $name,
        'age' => $age,
        'dob' => $dob,
        'contact' => $contact
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
