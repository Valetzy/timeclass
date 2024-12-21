<?php
// add_department.php

// Include database connection file
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $acronym = trim($_POST['acronym']);
    $department_name = trim($_POST['department_name']);

    // Validate inputs
    if (empty($acronym) || empty($department_name)) {
        // Redirect back with an error message if validation fails
        header("Location: add_department.php?error=Please fill in all fields.");
        exit();
    }

    // Prepare and execute SQL insert statement
    $sql = "INSERT INTO department (acronym, department_name) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $acronym, $department_name);

        if ($stmt->execute()) {
            // Redirect to a success page or reload form with a success message
            header("Location: ../dean/department.php?success=Department added successfully.");
        } else {
            // Redirect back with an error message if insertion fails
            header("Location: add_department.php?error=Failed to add department.");
        }

        $stmt->close();
    } else {
        header("Location: add_department.php?error=Database error.");
    }
}

// Close database connection
$conn->close();
?>
