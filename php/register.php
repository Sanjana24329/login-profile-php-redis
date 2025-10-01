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
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Get POST data
$name     = $_POST['name'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$age      = isset($_POST['age']) ? (int)$_POST['age'] : '';
$dob      = $_POST['dob'] ?? '';
$contact  = $_POST['contact'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($password) || empty($age) || empty($dob) || empty($contact)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, age, dob, contact) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiss", $name, $email, $hashedPassword, $age, $dob, $contact);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registration successful!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
}
$stmt->close();
$conn->close();
?>
