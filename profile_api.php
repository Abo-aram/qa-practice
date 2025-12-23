<?php
session_start();
header("Content-Type: application/json");
require_once "db_config.php";

/**
 * Helper function to send JSON responses consistently
 */
function sendResponse($status, $message, $extra = []) {
    $response = array_merge(["status" => $status, "message" => $message], $extra);
    echo json_encode($response);
    exit;
}

// 1. Authentication Check
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    sendResponse("error", "Unauthorized");
}

$userId = $_SESSION["user_id"];
$method = $_SERVER["REQUEST_METHOD"];

// ================= GET PROFILE =================
if ($method === "GET") {
    try {
        $stmt = $pdo->prepare("
            SELECT full_name, email, bio, country, gender, newsletter, profile_pic
            FROM users WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            sendResponse("error", "User not found");
        }

        echo json_encode($user);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        sendResponse("error", "Database error: " . $e->getMessage());
    }
}

// ================= UPDATE PROFILE =================
if ($method === "POST") {
    // 2. Read the raw JSON input
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    // 3. Check if JSON decoding failed
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        sendResponse("error", "Invalid JSON format received");
    }

    // 4. Extract and Sanitize Data
    $name       = trim($data["full_name"] ?? "");
    $bio        = trim($data["bio"] ?? "");
    $country    = trim($data["country"] ?? "");
    $gender     = $data["gender"] ?? null;
    $newsletter = (isset($data["newsletter"]) && $data["newsletter"]) ? 1 : 0;
    $newPass    = $data["new_password"] ?? "";

    // 5. Basic Validation
    if (empty($name) || strlen($name) < 2) {
        http_response_code(400);
        sendResponse("error", "Name is too short or empty");
    }

    try {
        $pdo->beginTransaction();

        // Optional: Update Password if provided
        if (!empty($newPass)) {
            if (strlen($newPass) < 8) {
                http_response_code(400);
                sendResponse("error", "Password must be at least 8 characters");
            }
            $hashed = password_hash($newPass, PASSWORD_DEFAULT);
            $pwStmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
            $pwStmt->execute([$hashed, $userId]);
        }

        // Update Profile Fields
        $sql = "UPDATE users SET 
                full_name = :name, 
                bio = :bio, 
                country = :country, 
                gender = :gender, 
                newsletter = :newsletter 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'       => $name,
            ':bio'        => $bio,
            ':country'    => $country,
            ':gender'     => $gender,
            ':newsletter' => $newsletter,
            ':id'         => $userId
        ]);

        $pdo->commit();
        sendResponse("success", "Profile updated successfully for " . $name);

    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        sendResponse("error", "Update failed: " . $e->getMessage());
    }
}

// If neither GET nor POST
http_response_code(405);
sendResponse("error", "Method Not Allowed");