<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

include '../conn/conn.php';

// Initialize variables for start and end dates
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$_SESSION['start_date'] = $start_date;
$_SESSION['end_date'] = $end_date;

$start = $_SESSION['start_date'];
$end = $_SESSION['end_date'];

// Fetch teacher_id from session or GET
$t_id = $_GET['t_id'];

// Query to fetch subject details assigned to the teacher
$query = "SELECT s.floor, s.s_id, c.c_id, s.subject, c.course, c.year_level, s.room, s.days, s.start_time, s.end_time 
          FROM assign_subject AS ass
          INNER JOIN subjects AS s ON ass.subject_id = s.s_id
          INNER JOIN courses AS c ON ass.course_id = c.c_id 
          WHERE teacher_id = '$t_id'";

$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TIMECLASS</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include("navigations/sidebar.php"); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include("navigations/header.php") ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Subject List</h1>
                    </div>


                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Attendance Lists</h6>
                            <div>
                                <form action="" method="post">
                                    <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
                                    <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </form>
                            </div>
                            <form action="download_pdf.php" method="post">
                                <input type="hidden" name="t_id" value="<?php echo $t_id ?>">
                                <input type="hidden" name="start" value="<?php echo $start ?>">
                                <input type="hidden" name="end" value="<?php echo $end ?>">
                                <button type="submit" class="btn btn-primary">Download PDF</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            </div>
                        </div>
                    </div>




                    <?php include("navigations/footer.php") ?>

                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="../controller/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <!-- Page level plugins -->
            <script src="vendor/chart.js/Chart.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/chart-area-demo.js"></script>
            <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>