<?php
// Replace with your database connection details
$conn = mysqli_connect("localhost", "root", "arsn@99", "attendance_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the attendance data is set
    if (isset($_POST['attendance'])) {
        // Loop through each student's attendance data
        foreach ($_POST['attendance'] as $student_id => $attendance_status) {
            // Update the attendance status in the database
            $sql = "UPDATE students SET attendance_status = '$attendance_status' WHERE id = $student_id";
            if ($conn->query($sql) === TRUE) {
                $response['success'] = true;
                $response['message'] = "Attendance marked successfully!";
            } else {
                $response['success'] = false;
                $response['error'] = "Error updating attendance: " . $conn->error;
            }
        }
    } else {
        $response['success'] = false;
        $response['error'] = "No attendance data received";
    }

    // Close database connection
    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
