<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 4) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

include '../conn/conn.php';

// Fetch teacher_id from session
$t_id = $_GET['t_id'];

// Query to fetch data
$query = "SELECT s.floor, s.s_id, c.c_id, s.subject, c.course, c.year_level, s.room, s.days, s.start_time, s.end_time FROM assign_subject AS ass
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
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lists</h6>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if any data is returned
                                        if (mysqli_num_rows($result) > 0) {
                                            // Fetch data and display it
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <tr>
                                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                                <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                                <td><?php echo htmlspecialchars($row['floor']); ?></td>
                                                <td><?php echo htmlspecialchars($row['room']); ?></td>
                                                    <td>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#reports">
                                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i>
                                                            View
                                                        </a>
                                                    </td>
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
            <div class="modal fade" id="signout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

            <!-- Reports Modal-->
            <div class="modal fade" id="reports" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reports</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <p>Present: </p>
                            <p>Late: </p>
                            <p>Absent: </p>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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