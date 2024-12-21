<?php

// Include database connection file
require_once '../conn/conn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the subject ID, course ID, teacher ID, and year level from the form
    $subject_id = $_POST['subject'];
    $course_id = $_POST['course'];
    $year_level = $_POST['year_level']; // Get year level
    $teacher_id = $_POST['teacher_id'];

    // Check if all IDs are not empty
    if (!empty($subject_id) && !empty($course_id) && !empty($teacher_id) && !empty($year_level)) {
        // Check if the record already exists
        $check_sql = "SELECT COUNT(*) FROM assign_subject WHERE subject_id = ? AND teacher_id = ? AND course_id = ? AND school_year = ?";

        if ($check_stmt = $conn->prepare($check_sql)) {
            // Bind variables to the prepared statement as parameters
            $check_stmt->bind_param("iiis", $subject_id, $teacher_id, $course_id, $year_level);

            // Attempt to execute the prepared statement
            $check_stmt->execute();

            // Bind the result variable
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            // If record exists, show a message
            if ($count > 0) {
                echo "<script>alert('This subject is already assigned to this teacher for this course and year level.');window.location = '../dean/teachers_lists.php';</script>";
                exit();
            } else {
                // Prepare the SQL statement to insert the new record
                $sql = "INSERT INTO assign_subject (subject_id, teacher_id, course_id, school_year) VALUES (?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("iiis", $subject_id, $teacher_id, $course_id, $year_level);

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
        echo "<script>alert('Please select a subject, course, teacher, and year level.');window.location = '../dean/teachers_lists.php';</script>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
