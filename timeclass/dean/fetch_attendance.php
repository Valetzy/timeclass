<?php
session_start();
include '../conn/conn.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 3) {
    exit(); // Do nothing or handle the error as appropriate
}

if (isset($_POST['school_year'])) {
    $school_year = $_POST['school_year'];
    
    // Query to get attendance data based on the selected school year
    $sql = "SELECT t.firstname, t.lastname, c.course, s.room, s.subject, att.timestamp, att.status, att.description
            FROM attendance AS att
            INNER JOIN teacher AS t ON att.t_id = t.t_id
            INNER JOIN subjects AS s ON att.s_id = s.s_id
            INNER JOIN courses AS c ON att.c_id = c.c_id
            WHERE YEAR(att.timestamp) BETWEEN SUBSTRING_INDEX('$school_year', '-', 1) AND SUBSTRING_INDEX('$school_year', '-', -1)";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Output the attendance records as cards or rows
            // You can format this as needed
            echo '<div class="card">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . '</h5>
                        <p class="card-text">Course: ' . htmlspecialchars($row['course']) . '</p>
                        <p class="card-text">Room: ' . htmlspecialchars($row['room']) . '</p>
                        <p class="card-text">Subject: ' . htmlspecialchars($row['subject']) . '</p>
                        <p class="card-text">Date & Time: ' . htmlspecialchars($row['timestamp']) . '</p>
                        <p class="card-text">Status: ' . htmlspecialchars($row['status']) . '</p>
                    </div>
                </div>';
        }
    } else {
        echo 'No attendance records found for this school year.';
    }

    $conn->close();
}
?>
