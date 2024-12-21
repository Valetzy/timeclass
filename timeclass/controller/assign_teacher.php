<?php
// Start the session
session_start();

// Include database connection file
require_once '../conn/conn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the subject ID, course ID, and teacher ID from the form
    $subject_id = $_POST['subject'];
    $course_id = $_POST['course']; // Assuming you want to store course_id
    $teacher_id = $_POST['teacher_id'];

    // Check if all IDs are not empty
    if (!empty($subject_id) && !empty($course_id) && !empty($teacher_id)) {
        // Check if the record already exists
        $check_sql = "SELECT COUNT(*) FROM assign_subject WHERE subject_id = ? AND teacher_id = ? AND course_id = ?";

        if ($check_stmt = $conn->prepare($check_sql)) {
            // Bind variables to the prepared statement as parameters
            $check_stmt->bind_param("iii", $subject_id, $teacher_id, $course_id);

            // Attempt to execute the prepared statement
            $check_stmt->execute();

            // Bind the result variable
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            // If record exists, show a message
            if ($count > 0) {
                echo "<script>alert('This subject is already assigned to this teacher for this course.');window.location = '../dean/teachers_lists.php';</script>";
                exit();
            } else {
                // Prepare the SQL statement to insert the new record
                $sql = "INSERT INTO assign_subject (subject_id, teacher_id, course_id) VALUES (?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("iii", $subject_id, $teacher_id, $course_id);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Redirect to a success page or display a success message
                        echo "<script>alert('Assigned Successfully.');window.location = '../dean/teachers_lists.php';</script>";
                        exit();
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo "Failed to prepare the SQL statement.";
                }
            }
        } else {
            echo "Failed to prepare the check SQL statement.";
        }
    } else {
        echo "<script>alert('Please select a subject, course, and teacher.');window.location = '../dean/teachers_lists.php';</script>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
