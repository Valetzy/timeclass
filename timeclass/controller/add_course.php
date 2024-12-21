<?php
// Include the connection file
include("../conn/conn.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'courses', 'year_lvl', 'section', and 'school_year' fields are not empty
    if (!empty($_POST['courses']) && !empty($_POST['year_lvl']) && !empty($_POST['section']) && !empty($_POST['school_year'])) {
        // Prepare and bind the INSERT statement
        $stmt = $conn->prepare("INSERT INTO courses (course, year_level, section, school_year) VALUES (?, ?, ?, ?)"); // Added school_year
        $stmt->bind_param("ssss", $courseName, $yearLevel, $section, $school_year); // Updated to include school_year

        // Set parameters
        $courseName = $_POST['courses'];
        $yearLevel = $_POST['year_lvl'];
        $section = $_POST['section'];
        $school_year = $_POST['school_year']; // Added school_year

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Course added successfully.'); window.location.href='../dean/add_courses.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // If any of the fields is empty, display an alert and go back to the previous page
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
} else {
    // If the request method is not POST, display an alert and go back to the previous page
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

// Close the connection
$conn->close();
?>
