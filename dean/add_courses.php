<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 3) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}
include("../conn/conn.php");
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
                        <h1 class="h3 mb-0 text-gray-800">Add Courses</h1>
                    </div>

                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Add Courses!</h1>
                                </div>
                                <form action="../controller/add_course.php" class="user" method="post">
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user mb-3"
                                                name="courses" id="exampleCourse" placeholder="Course">
                                            <!-- Department Select -->

                                            <?php
                                            $query = "SELECT * FROM department";
                                            $result = mysqli_query($conn, $query);
                                            if (!$result) {
                                                echo "Error fetching departments: " . mysqli_error($conn);
                                            } else {
                                                echo '<select class="form-control" aria-label="Default select example" name="d_id">';
                                                echo '<option selected>Department</option>';
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['department_name'] . '</option>';
                                                }
                                                echo '</select>';
                                            }
                                            ?><br>
                                            <select class="form-control" name="year_lvl" id="exampleYearlevel">
                                                <option value="" disabled selected>Year Level</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select><br>

                                            <input type="text" class="form-control form-control-user mb-3"
                                                name="section" id="exampleSection" placeholder="Section">

                                            <!-- School Year Select -->

                                            <?php

                                            $query = "SELECT * FROM school_year";
                                            $result = mysqli_query($conn, $query);
                                            if (!$result) {
                                                echo "Error fetching school years: " . mysqli_error($conn);
                                            } else {
                                                echo '<select class="form-control " aria-label="Default select example" name="school_year">';
                                                echo '<option selected>School Year</option>';
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['start_year'] . '-' . $row['end_year'] . '">' . $row['start_year'] . '-' . $row['end_year'] . '</option>';
                                                }
                                                echo '</select>';
                                            }
                                            ?>


                                        </div>
                                    </div>

                                    <!-- Buttons at the bottom -->
                                    <div class="d-flex justify-content-center mt-4">
                                        <button style="margin-right: 15px;" type="submit"
                                            class="btn btn-info">Submit</button>
                                        <button type="button" class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <?php include("navigations/footer.php") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>