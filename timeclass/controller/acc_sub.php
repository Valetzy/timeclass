<?php
// Database credentials
include('../conn/conn.php');

// Check if 'acc' and 't_id' parameters are set in the URL
if(isset($_GET['acc']) && isset($_GET['t_id'])) {
    // Get the values of 'acc' and 't_id' parameters
    $acc = $_GET['acc'];
    $t_id = $_GET['t_id'];

    // Prepare SQL statement to update 't_id' field in 'subjects' table
    $sql = "UPDATE subjects SET t_id = ? WHERE s_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ii", $t_id, $acc);

    // Execute the update statement
    $stmt->execute();

    // Check if update was successful
    if ($stmt->affected_rows > 0) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo "No 'acc' or 't_id' parameter found in the URL";
}

// Close connection
$conn->close();
?>
