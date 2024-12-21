<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
    header("Location: login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}
include '../conn/conn.php';

function displayAttendanceTable($conn) {
    // SQL query to select data from the attendance table
    $sql = "SELECT t.firstname, t.lastname, c.course, s.room, s.subject, att.timestamp, att.status, att.description FROM attendance AS att
            INNER JOIN teacher AS t ON att.t_id = t.t_id
            INNER JOIN subjects AS s ON att.s_id = s.s_id
            INNER JOIN courses AS c ON att.c_id = c.c_id";
    
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
            $name = htmlspecialchars($row['firstname']) .' ' . htmlspecialchars($row['lastname']);
            $course = htmlspecialchars($row['course']);
            $room = htmlspecialchars($row['room']);
            $subject = htmlspecialchars($row['subject']);
            $date_time = htmlspecialchars($row['timestamp']);
            $remarks = htmlspecialchars($row['status']);
            $description = htmlspecialchars($row['description']);

            if (empty($description)) {
                $no_des = 'NONE';
            } else {
                $no_des = $description; 
            }
            
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
                    <td>' . $no_des . '</td>
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

        <?php include ("navigations/sidebar.php"); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include ("navigations/header.php") ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
                    </div>

                    <?php displayAttendanceTable($conn); ?>
                    
                    <?php include ("navigations/footer.php") ?>

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