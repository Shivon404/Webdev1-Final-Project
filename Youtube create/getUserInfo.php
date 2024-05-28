<?php
// Start a new or resume the existing session
session_start();

// Set response to application/json aron e indicate the response will be in JSON format
header('Content-Type: application/json');

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user session variable is set, indicating the user is logged in
if (!isset($_SESSION['user'])) {
    // If the user is not logged in, output an error message in JSON format and terminate the script
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Include the database connection file; adjust the path if necessary
require_once '../Youtube login/database.php';

// Retrieve the user's email from the session
$email = $_SESSION['user'];

// Prepare an SQL statement to select the user's first name, last name, and username from the users table
$sql = "SELECT fname, lname, username FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

// Check if the SQL statement was prepared successfully
if (!$stmt) {
    // If the preparation failed, output an error message in JSON format and terminate the script
    echo json_encode(["error" => "SQL prepare statement failed: " . mysqli_error($conn)]);
    exit();
}

// Bind the user's email to the SQL statement as a parameter
mysqli_stmt_bind_param($stmt, "s", $email);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the result set from the executed statement
$result = mysqli_stmt_get_result($stmt);

// Check if the result set is valid
if ($result) {
    // Fetch the user's data as an associative array
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        // If the user data is found, combine the first and last names and retrieve the username
        $fullName = $user['fname'] . ' ' . $user['lname'];
        $username = $user['username'];
        // Output the user's full name and username in JSON format
        echo json_encode(["fullName" => $fullName, "username" => $username]);
    } else {
        // If no user data is found, output an error message in JSON format
        echo json_encode(["error" => "User not found"]);
    }
} else {
    // If the query execution failed, output an error message in JSON format
    echo json_encode(["error" => "Error executing query: " . mysqli_error($conn)]);
}

// Close the prepared statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);
?>
