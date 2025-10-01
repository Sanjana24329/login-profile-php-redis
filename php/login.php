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
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

// Fetch user from MySQL
$stmt = $conn->prepare("SELECT password, name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $stmt->close();
    $conn->close();
    exit;
}

// Bind result properly
$stmt->bind_result($hashedPassword, $name);
$stmt->fetch();

// Verify password
if (!password_verify($password, $hashedPassword)) {
    echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    $stmt->close();
    $conn->close();
    exit;
}

// Generate session token and save in Redis
$token = bin2hex(random_bytes(16));
setSession($token, $email);

// Optionally set cookie
setcookie("session_token", $token, time()+3600, "/");

// ✅ Return JSON with session token, name, and email
echo json_encode([
    'success' => true,
    'session_token' => $token,
    'email' => $email,
    'name' => $name
]);

$stmt->close();
$conn->close();
?>
