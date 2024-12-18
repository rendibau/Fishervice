<?php 
header("Access-Control-Allow-Origin: http://13.236.116.101:8002");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? null;
    $email = $_POST['email'] ?? null;

    // Logging data yang diterima
    error_log("Received fullname: $fullname, email: $email");

    if ($fullname && $email) {
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;

        $response = ["status" => "success", "message" => "Data diterima"];
    } else {
        $response = ["status" => "error", "message" => "Invalid data"];
    }

    // Log response untuk debugging
    error_log("Response: " . json_encode($response));

    echo json_encode($response);
    exit();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit();
}