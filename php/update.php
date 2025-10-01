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
$age = isset($_POST['age']) ? (int)$_POST['age'] : '';
$dob = $_POST['dob'] ?? '';
$contact = $_POST['contact'] ?? '';

if (empty($email) || empty($token) || empty($age) || empty($dob) || empty($contact)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Verify session in Redis
$storedEmail = getSession($token);
if ($storedEmail !== $email) {
    echo json_encode(['success' => false, 'message' => 'Invalid session']);
    exit;
}

// Update user info
$stmt = $conn->prepare("UPDATE users SET age = ?, dob = ?, contact = ? WHERE email = ?");
$stmt->bind_param("isss", $age, $dob, $contact, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
