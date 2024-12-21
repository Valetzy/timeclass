<?php
session_start();

if (!isset($_POST['school_year'])) {
    echo 'Error: School year not provided';
    exit();
}

include '../conn/conn.php';

$schoolYear = $_POST['school_year'];

// Function to display attendance records based on the school year
function displayAttendanceTable($conn, $schoolYear) {
    // SQL query to select data from the attendance table filtered by the selected school year
    $sql = "SELECT t.firstname, t.lastname, c.course, s.room, s.subject, att.timestamp, att.status, att.description 
            FROM attendance AS att
            INNER JOIN teacher AS t ON att.t_id = t.t_id
            INNER JOIN subjects AS s ON att.s_id = s.s_id
            INNER JOIN courses AS c ON att.c_id = c.c_id
            WHERE att.school_year = '$schoolYear'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start the table HTML
        echo '<!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Attendance</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Room</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Date&Time</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Room</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Date&Time</th>
                                        <th>Remarks</th>
                                    </tr>
                                </tfoot>
                                <tbody>';

        // Fetch each row and output it
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']);
            $course = htmlspecialchars($row['course']);
            $room = htmlspecialchars($row['room']);
            $subject = htmlspecialchars($row['subject']);
            $date_time = htmlspecialchars($row['timestamp']);
            $remarks = htmlspecialchars($row['status']);
            $description = htmlspecialchars($row['description']) ?: 'NONE';

            // Determine the button class based on remarks
            $btnClass = '';
            $btnText = '';

            switch ($remarks) {
                case 'ontime':
                    $btnClass = 'btn-success';
                    $btnText = 'Ontime';
                    break;
                case 'absent':
                    $btnClass = 'btn-danger';
                    $btnText = 'Absent';
                    break;
                case 'late':
                    $btnClass = 'btn-warning';
                    $btnText = 'Late';
                    break;
                default:
                    $btnClass = 'btn-secondary';
                    $btnText = 'Unknown';
                    break;
            }

            // Output the row
            echo '<tr>
                    <td>' . $name . '</td>
                    <td>' . $course . '</td>
                    <td>' . $room . '</td>
                    <td>' . $subject . '</td>
                    <td>' . $description . '</td>
                    <td>' . $date_time . '</td>
                    <td><a href="#" class="btn ' . $btnClass . ' btn-icon-split">
                        <span class="text">' . $btnText . '</span>
                    </a></td>
                </tr>';
        }

        // End the table HTML
        echo '        </tbody>
                    </table>
                </div>
            </div>
        </div>';
    } else {
        echo 'No attendance records found.';
    }
}

// Display the filtered attendance table
displayAttendanceTable($conn, $schoolYear);
?>
