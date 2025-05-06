<?php
// Include database connection
require_once 'db_connection.php';

// Define response array
$response = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, redirect to another page
            header("Location: index.php");
            exit();
        } else {
            $response['success'] = false;
            $response['message'] = "Invalid username or password";
            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid username or password";
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

