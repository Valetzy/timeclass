<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 4) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

include '../conn/conn.php';

function countEntries($tableName, $cardTitle)
{
    // Database credentials
    include '../conn/conn.php';
    // SQL query to count the number of entries in the specified table
    $sql = "SELECT COUNT(*) as entry_count FROM $tableName";
    $result = $conn->query($sql);

    // Fetch the result
    $entry_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $entry_count = $row['entry_count'];
    }

    // Close the connection
    $conn->close();

    // Return the HTML code with the count inserted
    return '
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ' . $cardTitle . '</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $entry_count . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function teachers($tableName, $cardTitle)
{
    // Database credentials
    include '../conn/conn.php';
    // SQL query to count the number of entries in the specified table
    $sql = "SELECT COUNT(*) as entry_count FROM $tableName WHERE confirmation = 1";
    $result = $conn->query($sql);

    // Fetch the result
    $entry_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $entry_count = $row['entry_count'];
    }

    // Close the connection
    $conn->close();

    // Return the HTML code with the count inserted
    return '
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ' . $cardTitle . '</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $entry_count . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function displayAttendanceTable($conn) {
    // SQL query to select data from the attendance table
    $sql = "SELECT * FROM teacher ";
    
    // Log the query
    error_log("Executing SQL query: $sql");

    // Execute the query and check for errors
    if (!$result = $conn->query($sql)) {
        // Log the error if query fails
        error_log("Error executing query: " . $conn->error);
        echo 'Error retrieving attendance records.';
        return;
    }

    if ($result->num_rows > 0) {
        // Start the table HTML
        echo '<!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>';
        
        // Fetch each row and output it
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['firstname']) .' ' . htmlspecialchars($row['lastname']);
            $t_id = htmlspecialchars($row['t_id']);

            // Log the fetched data
            error_log("Fetched row: Name - $name");

            // Output the row
            echo '<tr>
                    <td>' . $name . '</td>
                    <td> <a href="teachers_subject.php?t_id='. $t_id .'" class="btn btn-primary" > View </a> </td>
                </tr>';
        }

        // End the table HTML
        echo '        </tbody>
                    </table>
                </div>
            </div>
        </div>';
    } else {
        // Log if no records are found
        error_log("No attendance records found.");
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
                        <h1 class="h3 mb-0 text-gray-800">Teacher's List</h1>
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