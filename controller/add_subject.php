<?php
include("../conn/conn.php");
include('../phpqrcode/qrlib.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room = $_POST['room'];
    $days = isset($_POST['days']) ? $_POST['days'] : [];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $floor = $_POST['floor'];
    $subject = $_POST['subject'];
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester']; // Added semester variable

    if (empty($room) || empty($days) || empty($start_time) || empty($end_time) || empty($floor) || empty($subject) || empty($school_year) || empty($semester)) { // Check if semester is empty
        echo "All fields are required.";
    } else {
        $days_string = implode(",", $days);
        $courses = $_POST['courses']; // Retrieve selected course

        // Check if subject already exists
        $check_sql = "SELECT * FROM subjects WHERE room = ? AND subject = ? AND course = ? AND semester = ? AND school_year = ?"; // Updated to check semester and school_year
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("sssss", $room, $subject, $courses, $semester, $school_year); // Added semester and school_year to the bind_param
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Subject already exists.'); window.location='../dean/subject.php';</script>";
            exit(); // Stop execution if subject already exists
        }

        // Subject does not exist, proceed with insertion
        $qr_data = "$subject"; // Include subject in QR code data

        // Generate unique identifier for this QR code
        $unique_identifier = uniqid();

        $qr_code_path = "../qrcodes/{$room}_{$unique_identifier}_qrcode.png"; // Adjusted QR code path to include unique identifier

        QRcode::png($qr_data, $qr_code_path, QR_ECLEVEL_L, 4, 4);

        // Updated SQL to include school_year and semester
        $insert_sql = "INSERT INTO subjects(room, days, start_time, end_time, floor, qr_code, course, subject, unique_identifier, school_year, semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Added semester
        $insert_stmt = $conn->prepare($insert_sql);

        // Updated bind_param to include school_year and semester
        $insert_stmt->bind_param("sssssssssss", $room, $days_string, $start_time, $end_time, $floor, $qr_code_path, $courses, $subject, $unique_identifier, $school_year, $semester); // Added semester

        if ($insert_stmt->execute()) {
            echo "<script>alert('Subject added successfully.'); window.location='../dean/add_subject.php';</script>";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }

        $insert_stmt->close();
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
