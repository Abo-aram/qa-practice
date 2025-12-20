<?php
session_start();
header("Content-Type: application/json");
require_once "db_config.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$userId = $_SESSION["user_id"];

// ================= GET PROFILE =================
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $pdo->prepare("
        SELECT full_name, email, bio, country, gender, newsletter, profile_pic
        FROM users WHERE id = ?
    ");
    $stmt->execute([$userId]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}

// ================= UPDATE PROFILE =================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name       = trim($_POST["full_name"] ?? "");
    $bio        = trim($_POST["bio"] ?? "");
    $country    = $_POST["country"] ?? "";
    $gender     = $_POST["gender"] ?? null;
    $newsletter = isset($_POST["newsletter"]) ? 1 : 0;
    $newPass    = $_POST["new_password"] ?? "";

    if (strlen($name) <= 1) {
        echo json_encode(["status" => "error", "message" => "Name too short"]);
        exit;
    }

    // Password change (optional)
    if ($newPass !== "") {
        if (strlen($newPass) < 8) {
            echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters"]);
            exit;
        }
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password=? WHERE id=?")
            ->execute([$hashed, $userId]);
    }

    // Update profile
    $stmt = $pdo->prepare("
        UPDATE users SET
        full_name=?, bio=?, country=?, gender=?, newsletter=?
        WHERE id=?
    ");
    $stmt->execute([$name, $bio, $country, $gender, $newsletter, $userId]);

    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
}
