<?php
include("../conn/conn.php");

// Process form data
if(isset($_POST["save"])) {
    // Assign form data to variables
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $password = $_POST['password'];
    $password_confirmation = $_POST['confirm_password'];
    $department = $_POST['department'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $confirmation = 0;

    if($password !=  $password_confirmation) {
        echo "<script>alert('confirm password does not match');</script>";
        echo "<script>window.location.href='../register.php';</script>";
        exit();
    }


    $passwords = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = 1;

    // Check if the email already exists
    $check_sql = "SELECT * FROM teacher WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists');</script>";
        echo "<script>window.location.href='../register.php';</script>";
        exit(); // stop further execution
    } else {
        // Prepare an SQL statement
        $insert_sql = "INSERT INTO teacher (firstname, lastname, username, password, user_type, confirmation, contactnumber, department, address)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($insert_sql);
        
        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssssss", $firstname, $lastname, $email, $passwords, $user_type, $confirmation, $contact, $department, $address);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Registered Succesfully');</script>";
            echo "<script>window.location.href='../login.php';</script>";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement
    $check_stmt->close();
}

// Close the database connection
$conn->close();
