<?php
// Include the database connection file
include("../conn/conn.php");

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values
    $start_year = isset($_POST['start_year']) ? intval($_POST['start_year']) : 0;
    $end_year = isset($_POST['end_year']) ? intval($_POST['end_year']) : 0;

    // Check if both years are provided and valid
    if ($start_year > 0 && $end_year > 0 && $start_year < $end_year) {
        // Prepare an SQL statement to insert the data
        $stmt = $conn->prepare("INSERT INTO school_year (start_year, end_year) VALUES (?, ?)");
        $stmt->bind_param("ii", $start_year, $end_year);

        // Execute the statement and check if the insertion was successful
        if ($stmt->execute()) {
            // Success message (you can also use SweetAlert here if needed)
            echo "<script>alert('School year added successfully!'); window.location.href='school_year.php';</script>";
        } else {
            // Error message
            echo "<script>alert('Error: Could not add the school year.'); window.history.back();</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Invalid input message
        echo "<script>alert('Please enter valid start and end years.'); window.history.back();</script>";
    }
}

// Close the database connection
$conn->close();
?>
