<?php
include("../conn/conn.php");

if (isset($_POST['t_id'])) {
    $teacher_id = intval($_POST['t_id']);

    $sql = "SELECT s.subject, c.course FROM assign_subject AS ass 
            INNER JOIN subjects AS s ON ass.subject_id = s.s_id
            INNER JOIN courses AS c ON ass.course_id = c.c_id
            WHERE ass.teacher_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card bg-primary text-white mb-2">
                    <div class="card-body">
                        ' . htmlspecialchars($row['subject']) . '
                        <div class="text-white-50 small">' . htmlspecialchars($row['course']) . '</div>
                    </div>
                  </div>';
        }
    } else {
        echo '<p>No subjects assigned</p>';
    }

    $stmt->close();
    $conn->close();
}
?>
