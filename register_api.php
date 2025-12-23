<?php
header("Content-Type: application/json");
require_once 'db_config.php';

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['fullName'] ?? '';
$email = $data['email'] ?? '';
$pass = $data['password'] ?? '';


if (!empty($name) || !empty($email) || !empty($pass)) {
    echo json_encode(["message" => $data]);
       if(strlen($name) <=1){
        http_response_code(400); // Bad Request\
        echo json_encode(["message" => "Name must be more than 1 character!"]);
        exit;
    }
   
    //validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {    
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Invalid email format!"]);
        exit;
    }
    if(strlen($pass) < 8){
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Password must be at least 8 characters long!"]);
        exit;
    }
    
    if(!preg_match('/[a-zA-z0-9]+$/', $pass)){
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Password not valid!"]);
        exit;
    }

 

    
}else{
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "All fields are required!"]);


    exit;

}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409); // Conflict (Email already exists)
        echo json_encode(["message" => "This email is already in our system."]);
        exit;
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPass]);

    http_response_code(201); // Created
    echo json_encode(["message" => "User account created successfully!"]);

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["message" => "Database error occurred."]);
}
?>