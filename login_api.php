<?php
// 1. MUST BE FIRST: Start the session before any headers or output
session_start(); 

header("Content-Type: application/json");
require_once 'db_config.php'; 

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$pass = $data['password'] ?? '';

if (empty($email) || empty($pass)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Fields cannot be empty"]);
    exit;
}

try {
    // FIX: Added 'full_name' to the SELECT statement
    $stmt = $pdo->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        // 2. Set Session Variables (Now full_name exists!)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Login successful!"]);
    } else {
        http_response_code(401); 
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }

} catch (PDOException $e) {
    http_response_code(500); 
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>