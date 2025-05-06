<?php
// Include Twilio PHP SDK
require_once __DIR__ . '/vendor/autoload.php';

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

// Twilio credentials
$twilio_sid = getenv('TWILIO_SID');
$twilio_token = getenv('TWILIO_AUTH_TOKEN');
$twilio_phone_number = getenv('TWILIO_PHONE');

// Replace with your database connection details
$conn = mysqli_connect("localhost", "root", "arsn@99", "attendance_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the send_sms parameter is set
    if (isset($_POST['send_sms'])) {
        // Get absent students' parent numbers
        $sql = "SELECT parent_number FROM students WHERE attendance_status='Absent'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $twilio = new Client($twilio_sid, $twilio_token);

            // Send SMS to absent students' parents
            while ($row = $result->fetch_assoc()) {
                // Format Indian phone number with country code
                $parent_number = '+91' . $row['parent_number'];
                $message = 'Dear parent,your daughter/son is marked as absent today ';
                
                try {
                    // Send SMS using Twilio
                    $twilio->messages->create(
                        $parent_number,
                        array(
                            'from' => $twilio_phone_number,
                            'body' => $message
                        )
                    );
                    $response['sms_success'] = true; // SMS sent successfully
                } catch (TwilioException $e) {
                    $response['sms_success'] = false; // Failed to send SMS
                    $response['sms_errors'][] = "Failed to send SMS to $parent_number: " . $e->getMessage();
                    // Log the error for debugging purposes
                    error_log("Twilio error: " . $e->getMessage());
                }
            }
        } else {
            $response['sms_success'] = false; // No absent students found
            $response['sms_errors'][] = "No absent students found.";
            // Log the error for debugging purposes
            error_log("No absent students found.");
        }
    }
}

$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>