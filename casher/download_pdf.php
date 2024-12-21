<?php
require 'vendor/autoload.php'; // If using Composer
use Dompdf\Dompdf;

// Start output buffering
ob_start();

include '../conn/conn.php';

$t_id = isset($_POST['t_id']) ? $_POST['t_id'] : '';
$start = isset($_POST['start']) ? $_POST['start'] : '';
$end = isset($_POST['end']) ? $_POST['end'] : '';

// Query to fetch subject details assigned to the teacher
$query = "SELECT s.floor, s.s_id, c.c_id, s.subject, c.course, c.year_level, s.room, s.days, s.start_time, s.end_time 
          FROM assign_subject AS ass
          INNER JOIN subjects AS s ON ass.subject_id = s.s_id
          INNER JOIN courses AS c ON ass.course_id = c.c_id 
          WHERE teacher_id = '$t_id'";

$result = mysqli_query($conn, $query);
?>

<!-- HTML content for the PDF (reuse your table code here) -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance List PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Attendance Lists</h1>
    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>Year Level</th>
                <th>Subject</th>
                <th>Floor</th>
                <th>Room</th>
                <th>Present</th>
                <th>Late</th>
                <th>Absent</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if any data is returned
            if (mysqli_num_rows($result) > 0) {
                // Fetch data and display it
                while ($row = mysqli_fetch_assoc($result)) {
                    // Prepare the attendance query based on whether dates are selected
                    $subject_id = $row['s_id'];
                    if (!empty($start) && !empty($end)) {
                        // Calculate the total number of days between the start and end date
                        $start_timestamp = strtotime($start);
                        $end_timestamp = strtotime($end);
                        $total_days = floor(($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;

                        // Filter attendance by selected date range
                        $attendance_query = "SELECT 
                                                        COUNT(CASE WHEN status = 'Ontime' THEN 1 END) AS present_count, 
                                                        COUNT(CASE WHEN status = 'Late' THEN 1 END) AS late_count
                                                    FROM attendance 
                                                    WHERE s_id = '$subject_id' 
                                                    AND date BETWEEN '$start' AND '$end'";

                        $attendance_result = mysqli_query($conn, $attendance_query);
                        $attendance_data = mysqli_fetch_assoc($attendance_result);

                        // Calculate the absent count
                        $present_count = $attendance_data['present_count'];
                        $late_count = $attendance_data['late_count'];
                        $attended_count = $present_count + $late_count;
                        $absent_count = $total_days - $attended_count;
                    } else {
                        // No date filter, get all attendance data
                        $attendance_query = "SELECT 
                                                        COUNT(CASE WHEN status = 'Ontime' THEN 1 END) AS present_count, 
                                                        COUNT(CASE WHEN status = 'Late' THEN 1 END) AS late_count
                                                    FROM attendance 
                                                    WHERE s_id = '$subject_id'";

                        $attendance_result = mysqli_query($conn, $attendance_query);
                        $attendance_data = mysqli_fetch_assoc($attendance_result);
                        // Since there's no date range, assume a fixed total number of days (e.g., 15)
                        $present_count = $attendance_data['present_count'];
                        $late_count = $attendance_data['late_count'];

                        $absent_count = '####';

                    }

                    // Display the subject and attendance data
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['floor']); ?></td>
                        <td><?php echo htmlspecialchars($row['room']); ?></td>
                        <td><?php echo htmlspecialchars($present_count); ?></td>
                        <td><?php echo htmlspecialchars($late_count); ?></td>
                        <td><?php echo htmlspecialchars($absent_count); ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<div class="alert alert-warning" role="alert">No subjects assigned.</div>';
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>

</html>

<?php
$html = ob_get_clean();

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Set the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream("attendance_list.pdf", ["Attachment" => 1]);
