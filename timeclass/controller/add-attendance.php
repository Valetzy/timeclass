<?php
// Include database connection file
include('../conn/conn.php');

// Function to sanitize data
function sanitizeData($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the QR code from the form
    $qr_code = sanitizeData($_POST['qr_code']);
    $user_id = sanitizeData($_POST['user_id']);
    
    // Validate the QR code (you may add more validation logic based on your requirements)
    if (empty($qr_code)) {
        // If the QR code is empty, return an error message
        echo "QR code cannot be empty.";
        exit();
    }
    
    // Prepare the SQL query to insert data into the attendance table
    $query = "INSERT INTO qr_data (qr_code, user_id) VALUES (?, ?)";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $qr_code, $user_id);
    
    // Execute the query and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('Attendance Added Successfully');</script>";
        echo "Attendance added successfully.";
        // Redirect the user back to the dashboard or any other page as required
        
        header("Location: ../teacher/teacher_db.php");
        exit();
    } else {
        echo "Error adding attendance: " . $stmt->error;
    }
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
