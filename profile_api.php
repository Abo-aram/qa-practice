<?php
session_start();
header("content-type:application/json");
require_once 'db_config.php';

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    echo json_encode("message","unauthorized");
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

try{
    $sql = "UPDATE users SET bio = ?, country= ?, gender=?, newsletter=? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data['bio'] ?? 'not set',
        $data['country'] ?? 'not set',
        $data['gender'] ?? 'not set',
        $data['newsletter'] ? 1 : 0,
        $_SESSION['user_id']

    ]);

    http_response_code(200);
    echo json_encode(["message"=>"profile updated successfully"]);


}catch (PDOException $e){
    http_response_code(500);
    echo json_encode(["message"=>"failed to update profile"]);
}




?>