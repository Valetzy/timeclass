<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 1) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

// Include the database connection file
include('../conn/conn.php');

$teacher_id = $_SESSION['t_id'];
// Query to select all data from the attendance table
$query = "SELECT c.course, att.room, att.subject, att.timestamp, att.status, att.t_id, att.description, att.snapchat FROM attendance AS att
          INNER JOIN courses AS c ON att.c_id = c.c_id
          WHERE att.t_id = $teacher_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch all rows
$attendances = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include "navigations/sidebar.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "navigations/header.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Reports</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Attendance Reports</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>    
                                            <th>Course</th>
                                            <th>Room</th>
                                            <th>Subject</th>
                                            <th>Date & Time</th>
                                            <th>Description</th>
                                            <th>Snapshot</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Course</th>
                                            <th>Room</th>
                                            <th>Subject</th>
                                            <th>Date & Time</th>
                                            <th>Description</th>
                                            <th>Snapshot</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach ($attendances as $attendance): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($attendance['course']); ?></td>
                                                <td><?php echo htmlspecialchars($attendance['room']); ?></td>
                                                <td><?php echo htmlspecialchars($attendance['subject']); ?></td>
                                                <td><?php echo htmlspecialchars($attendance['timestamp']); ?></td>
                                                <td>
                                                    <?php 
                                                        if (empty(htmlspecialchars($attendance['description']))) {
                                                            echo 'none';
                                                        } else {
                                                            echo htmlspecialchars($attendance['description']);
                                                        }                                                        
                                                    ?>
                                                </td>
                                                <td><img src="uploads/<?php echo htmlspecialchars($attendance['snapchat']); ?>" alt="photo" width="100px" height="100px" ></td>
                                                <td>
                                                    <?php
                                                        // Assume $attendance['status'] contains the current status value
                                                        $status = htmlspecialchars($attendance['status']);

                                                        if ($status == 'ontime') {
                                                            echo '<button class="btn btn-success btn-icon-split">
                                                                    <span class="text">Ontime</span>
                                                                </button>';
                                                        } elseif ($status == 'late') {
                                                            echo '<button class="btn btn-warning btn-icon-split">
                                                                    <span class="text">Late</span>
                                                                </button>';
                                                        } else {
                                                            echo '<button class="btn btn-secondary btn-icon-split">
                                                                    <span class="text">Absent</span>
                                                                </button>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "navigations/logout.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
