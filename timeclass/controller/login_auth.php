<?php
session_start();
include('../conn/conn.php');

if (isset($_POST['save'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check admin table
    $admin_query = "SELECT * FROM admin WHERE username='$username'";
    $admin_result = mysqli_query($conn, $admin_query);

    // Query to check teacher table
    $teacher_query = "SELECT * FROM teacher WHERE username='$username'";
    $teacher_result = mysqli_query($conn, $teacher_query);

    // Query to check dean table
    $dean_query = "SELECT * FROM dean WHERE username='$username'";
    $dean_result = mysqli_query($conn, $dean_query);

    // Query to check casher table
    $casher_query = "SELECT * FROM casher WHERE username='$username'";
    $casher_result = mysqli_query($conn, $casher_query);

    if (mysqli_num_rows($admin_result) == 1) {
        $admin_row = mysqli_fetch_assoc($admin_result);
        if (password_verify($password, $admin_row['password'])) {
            $_SESSION['user_type'] = 2; // Admin user type
            $_SESSION['a_id'] = $admin_row['a_id'];
            $_SESSION['username'] = $admin_row['username'];
            header("Location: ../template/dashboard.php");
            exit();
        }
    } elseif (mysqli_num_rows($teacher_result) == 1) {
        $teacher_row = mysqli_fetch_assoc($teacher_result);
        if (password_verify($password, $teacher_row['password'])) {
            if ($teacher_row['confirmation'] == 1) {
                $_SESSION['user_type'] = 1; // Teacher user type
                $_SESSION['t_id'] = $teacher_row['t_id'];
                $_SESSION['username'] = $teacher_row['username'];
                $_SESSION['firstname'] = $teacher_row['firstname'];
                $_SESSION['lastname'] = $teacher_row['lastname'];
                header("Location: ../teacher/teacher_db.php");
                exit();
            } elseif ($teacher_row['confirmation'] == 2) {
                echo "<script>alert('Your account has been declined.');</script>";
                echo "<script>window.location.href='../login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Account needs Approval!');</script>";
                echo "<script>window.location.href='../login.php';</script>";
                exit();
            }
        }
    } elseif (mysqli_num_rows($dean_result) == 1) {
        $dean_row = mysqli_fetch_assoc($dean_result);
        if (password_verify($password, $dean_row['password'])) {
            $_SESSION['user_type'] = 3; // Dean user type
            $_SESSION['d_id'] = $dean_row['d_id'];
            $_SESSION['username'] = $dean_row['username'];
            $_SESSION['name'] = $dean_row['name'];
            header("Location: ../dean/dean_db.php");
            exit();
        }
    } elseif (mysqli_num_rows($casher_result) == 1) { // Fixed to check for 1 row
        $casher_row = mysqli_fetch_assoc($casher_result);
        if (password_verify($password, $casher_row['password'])) {
            $_SESSION['user_type'] = 4; // Casher user type, should be unique
            $_SESSION['c_id'] = $casher_row['c_id'];
            $_SESSION['username'] = $casher_row['username'];
            $_SESSION['name'] = $casher_row['name'];
            header("Location: ../casher/casher_db.php");
            exit();
        }
    }

    echo "<script>alert('Invalid Username and Password');</script>";
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}
?>
