<?php
session_start();
header('Content-Type: application/json'); // Ensure the response is JSON

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

require_once '../Youtube login/database.php'; // Adjust the path if necessary
$email = $_SESSION['user'];

// Check if the column names in the query match those in your database schema
$sql = "SELECT fname, lname, username FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL prepare statement failed: " . mysqli_error($conn)]);
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        $fullName = $user['fname'] . ' ' . $user['lname'];
        $username = $user['username'];
        echo json_encode(["fullName" => $fullName, "username" => $username]);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} else {
    echo json_encode(["error" => "Error executing query: " . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
