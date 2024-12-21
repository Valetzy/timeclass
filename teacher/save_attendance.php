<?php
session_start();
include('../conn/conn.php');

// Set the timezone to the Philippines
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the image and other required fields are set
    if (isset($_POST['data'], $_POST['s_id'], $_POST['subject'], $_POST['c_id'], $_POST['t_id'], $_POST['start_time'], $_POST['end_time'], $_POST['room'], $_POST['floor'], $_POST['image'])) {
        $scannedData = $_POST['data'];
        $s_id = $_POST['s_id'];
        $subject = $_POST['subject'];
        $c_id = $_POST['c_id'];
        $t_id = $_POST['t_id'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $room = $_POST['room'];
        $floor = $_POST['floor'];
        $image = $_POST['image'];

        // Save the image
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'selfie_' . $s_id . '_' . time() . '.png'; // Naming the image
        file_put_contents('uploads/' . $imageName, base64_decode($image)); // Save in the selfies folder

        // Proceed with attendance logic
        // Convert start and end time to a comparable format
        $currentDate = date('Y-m-d');
        $currentDateTime = date('H:i');
        $startDateTime = $currentDate . ' ' . $start_time;
        $endDateTime = $currentDate . ' ' . $end_time;

        // Get the current timestamp
        $currentTimestamp = date('Y-m-d H:i:s');
        $currentTime = date('H:i');

        // Define time limits for "ontime" status
        $startDateTimeObj = new DateTime($startDateTime);
        $latestOnTime = $startDateTimeObj->modify('+10 minutes')->format('H:i');

        // Determine the attendance status
        if ($scannedData === $subject) {
            if ($currentTime >= $start_time && $currentTime <= $end_time) {
                // Check if the teacher has already recorded attendance for this date
                $checkSql = "SELECT * FROM attendance WHERE t_id = ? AND subject = ? AND DATE(timestamp) = ?";
                if ($checkStmt = $conn->prepare($checkSql)) {
                    $checkStmt->bind_param('iss', $t_id, $subject, $currentDate);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<script>alert('Attendance for today has already been recorded.'); window.location='teacher_db.php';</script>";
                    } else {
                        // Determine the status based on the arrival time
                        if ($currentTime === $start_time || $currentTime <= $latestOnTime) {
                            $status = 'ontime';
                        } else {
                            $status = 'late';
                        }

                        // Prepare the SQL statement to insert the data into the attendance table
                        $sql = "INSERT INTO attendance (s_id, subject, c_id, t_id, scanned_data, start_time, end_time, room, floor, timestamp, status, snapchat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param('ssisssssssss', $s_id, $subject, $c_id, $t_id, $scannedData, $start_time, $end_time, $room, $floor, $currentTimestamp, $status, $imageName);

                            if ($stmt->execute()) {
                                echo "<script>alert('Attendance Successfully Recorded.'); window.location='teacher_db.php';</script>";
                            } else {
                                echo 'Error: ' . $stmt->error;
                            }

                            $stmt->close();
                        } else {
                            echo 'Error: ' . $conn->error;
                        }
                    }

                    $checkStmt->close();
                } else {
                    echo 'Error: ' . $conn->error;
                }
            } else {
                echo  "<script>alert('Subject not started yet!'); window.location='teacher_db.php';</script>";
            }
        } else {
            echo  "<script>alert('Invalid QR Code!'); window.location='teacher_db.php';</script>";
        }
    } else {
        echo "<script>alert('Missing Required Data!'); window.location='teacher_db.php';</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request method!'); window.location='teacher_db.php';</script>";
}
?>
