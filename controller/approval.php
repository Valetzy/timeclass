<?php
// Include database connection
include("../conn/conn.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if approve button is clicked
    if (isset($_POST["approve"])) {
        // Get teacher ID from the form
        $teacher_id = $_POST["t_id"];
        $email = $_POST["email"];
        
        // Perform update query to approve the teacher
        $sql = "UPDATE teacher SET confirmation = 1 WHERE t_id = $teacher_id";
        if ($conn->query($sql) === TRUE) {

            if ($conn->affected_rows) {
                $mail = require __DIR__ . "/mailer.php";
                $mail->setFrom("vodatankquiros@gmail.com");
                $mail->addAddress($email);
                $mail->Subject = "Password Reset";
                $mail->Body = <<<END
                
                <center>
                
                    <h1>Your Account in Timeclass is Approved!</h1> <br>
                    <p>You can now login in the Sibugay Technical Institute Timeclass Application :)</p>
                    <a class="btn btn-primary" href="https://192.168.2.4/timeclass/login.php">Log In to Timeclass</a>
                
                </center>

                END;
                try {
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                }
            }

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

