<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Ensure the background image covers the entire page */
        body {
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            background-image: url('https://visme.co/blog/wp-content/uploads/2017/07/50-Beautiful-and-Minimalist-Presentation-Backgrounds-08.jpg');
            background-repeat: no-repeat;
            height: 100vh; /* Ensure the background fills the entire viewport */
        }

        .header {
            background-color: pink;
            color: black;
            text-decoration-line: underline;
            text-decoration-color: green;  
            border: 2px solid black;
            outline: darkblue solid 10px;
            margin: auto;  
            padding: 20px;
            text-align: center;
        }

        .footer {
            color: black;
            padding: 20px;
            text-align: center;
            background-color: #f1f1f1; /* Light footer background */
        }

        .footer .location {
            margin-bottom: 10px;
        }

        .footer .social-media a {
            margin: 0 5px;
            color: black;
            font-size: 25px;
            text-align: center;
            font-weight: bolder;
        }

        .footer .social-media a img {
            width: 30px; 
            height: auto;
        }

        .content {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* White with opacity for readability */
            border-radius: 8px;
            margin-top: 50px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 20px;
        }

        .attendance-table th, .attendance-table td {
            border: 2px solid black;
            padding: 12px;
            text-align: center;
        }

        .attendance-table th {
            background-color: #f2f2f2; /* Light gray for header */
            color: black;
        }

        /* Center-align buttons */
        button[type="button"] {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background-color: white;
            border: 2px solid #4CAF50;
            color: black;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button[type="button"]:hover {
            background-color: lightblue;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .location {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Jerusalem College of Engineering</h1>
    </header>

    <div class="content">
        <h2>Attendance Management System</h2>
        <form id="attendanceForm">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Registration Number</th>  
                        <th>Year</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Include the database connection file
                        require_once 'db_connection.php';

                        // Fetch student data from the database
                        $sql = "SELECT * FROM students";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['student_name'] . "</td>";
                                echo "<td>" . $row['registration_number'] . "</td>"; 
                                echo "<td>" . $row['year'] . "</td>";
                                echo "<td><input type='radio' name='attendance[" . $row['id'] . "]' value='Present'></td>";
                                echo "<td><input type='radio' name='attendance[" . $row['id'] . "]'value='Absent'></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No student records found</td></tr>";
                        }

                        // Close database connection
                        $conn->close();
                    ?>
                </tbody>
            </table>
            <div class="button-container">
                <button type="button" onclick="submitAttendance()">Submit Attendance</button>
                <button type="button" onclick="sendSMS()">Send SMS to Parents</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="location">
            Location: Pallikaranai, Chennai-100
        </div>
        <div class="social-media">
        <a href="..."><img src="twitter.png" alt="Twitter"></a>
        <a href="..."><img src="instagram.png" alt="Instagram"></a>

            
        </div>
    </footer>

    <script>
        function submitAttendance() {
            var formData = new FormData(document.getElementById("attendanceForm"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "submit_attendance.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && response.success) {
                        alert("Attendance marked successfully!");
                    } else {
                        alert("Failed to mark attendance: " + response.error);
                    }
                }
            };
            xhr.send(new URLSearchParams(formData));
        }

        function sendSMS() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_sms.php", true); // Send request to send_sms.php
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && response.sms_success) {
                        alert("SMS sent successfully!");
                    } else {
                        alert("Failed to send SMS: " + response.sms_errors.join(', '));
                    }
                }
            };
            var formData = new FormData();
            formData.append('send_sms', true);
            xhr.send(new URLSearchParams(formData));
        }
    </script>
</body>
</html>
