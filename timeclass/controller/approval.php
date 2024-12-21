<?php
// Include database connection
include("../conn/conn.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if approve button is clicked
    if (isset($_POST["approve"])) {
        // Get teacher ID from the form
        $teacher_id = $_POST["t_id"];
        
        // Perform update query to approve the teacher
        $sql = "UPDATE teacher SET confirmation = 1 WHERE t_id = $teacher_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../template/approval.php");
        } else {
            echo "Error approving teacher: " . $conn->error;
        }
    }
    
    // Check if decline button is clicked
    if (isset($_POST["decline"])) {
     // Get teacher ID from the form
     $teacher_id = $_POST["t_id"];
     
     // Perform update query to approve the teacher
     $sql = "UPDATE teacher SET confirmation = 2 WHERE t_id = $teacher_id";
     if ($conn->query($sql) === TRUE) {
      header("Location: ../template/approval.php");
     } else {
         echo "Error approving teacher: " . $conn->error;
     }
 }
}
?>
