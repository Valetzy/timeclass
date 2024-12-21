<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
    header("Location: login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection
    include_once("../conn/conn.php");

    // Process the form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $major_subject = mysqli_real_escape_string($conn, $_POST['major_subject']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Validation and sanitization (example: you can add more validation rules)
    if (empty($name) || empty($birthdate) || empty($major_subject) || empty($gender) || empty($address)) {
        // Handle empty fields
        echo "All fields are required.";
        exit();
    }

    // Insert the data into the database using prepared statement
    $query = "INSERT INTO info (name, birthdate, major_subject, gender, address) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "sssss", $name, $birthdate, $major_subject, $gender, $address);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to a success page upon successful insertion
            echo "<script>window.alert('Information Added Successfully!')
            window.location='../template/add_info.php'</script>";

            exit();
        } else {
            // Handle database errors
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle statement preparation error
        echo "Error: Unable to prepare statement.";
    }

    // Close database connection
    mysqli_close($conn);
}
?>
