<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require 'redis.php'; // Redis helpers

// Check if session token exists
$token = $_COOKIE['session_token'] ?? '';

if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'No session token found. Please login.']);
    exit;
}

// Verify session token in Redis
$email = getSession($token);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Session expired or invalid. Please login again.']);
    exit;
}

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

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, age, dob, contact FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $email, $age, $dob, $contact);
$stmt->fetch();

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $stmt->close();
    $conn->close();
    exit;
}

// Return user info
echo json_encode([
    'success' => true,
    'user' => [
        'name' => $name,
        'email' => $email,
        'age' => $age,
        'dob' => $dob,
        'contact' => $contact
    ]
]);

$stmt->close();
$conn->close();
?>
