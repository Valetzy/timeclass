<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 3) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
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
                        <h1 class="h3 mb-0 text-gray-800">Teacher's Lists</h1>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lists</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Contact Number</th>
                                            <th>Assign</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include("../conn/conn.php");
                                        $sql = "SELECT t.firstname, t.lastname, d.acronym, t.contactnumber, t.t_id FROM teacher AS t INNER JOIN department AS d ON t.department = d.id WHERE confirmation = 1";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["firstname"] . " " . $row["lastname"] ?> </td>
                                                    <td><?php echo $row["acronym"] ?></td>
                                                    <td><?php echo $row["contactnumber"] ?></td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#assign"
                                                            class="btn btn-primary btn-icon-split"
                                                            data-teacher-id="<?php echo $row["t_id"]; ?>">
                                                            <span class="text">Add Subject</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No data found</td></tr>";
                                        }
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

            <!-- Assign Modal-->
            <div class="modal fade" id="assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Assign Subject</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../controller/assign_teacher.php" method="post">
                                <center>
                                    <select name="subject" class="form-select form-select-lg mb-3"
                                        aria-label="Large select example"
                                        style=" width: 300px; height: 50px; border-radius: 10px; border-color: #8c8c8c; border-bottom: groove;"
                                        required>
                                        <option selected>Assign Subject</option>
                                        <?php
                                        $subject_sql = "SELECT * FROM subjects";
                                        $subject_result = $conn->query($subject_sql);
                                        if ($subject_result->num_rows > 0) {
                                            while ($subject_row = $subject_result->fetch_assoc()) {
                                                echo '<option value="' . $subject_row["s_id"] . '">' . $subject_row["subject"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No subjects found</option>';
                                        }
                                        ?>
                                    </select>

                                    <select name="course" class="form-select form-select-lg mb-3"
                                        aria-label="Large select example"
                                        style=" width: 300px; height: 50px; border-radius: 10px; border-color: #8c8c8c; border-bottom: groove;"
                                        required>
                                        <option selected>Select Class</option>
                                        <?php
                                        $courses_sql = "SELECT * FROM courses";
                                        $courses_result = $conn->query($courses_sql);
                                        if ($courses_result->num_rows > 0) {
                                            while ($courses_row = $courses_result->fetch_assoc()) {
                                                echo '<option value="' . $courses_row["c_id"] . '">' . $courses_row["course"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No subjects found</option>';
                                        }
                                        ?>
                                    </select>

                                    <?php
                                    // Query to select school years from the table
                                    $year_lvl_query = "SELECT * FROM school_year";
                                    $year_level_result = mysqli_query($conn, $year_lvl_query);

                                    if (!$year_level_result) {
                                        echo "Error fetching school years: " . mysqli_error($conn);
                                    } else {
                                        echo '<select name="year_level" class="form-select form-select-lg mb-3"
                                                aria-label="Large select example"
                                                style=" width: 300px; height: 50px; border-radius: 10px; border-color: #8c8c8c; border-bottom: groove;"
                                                required>';
                                        echo '<option selected>School Year</option>';

                                        // Fetch each row and display as an option
                                        while ($row = mysqli_fetch_assoc($year_level_result)) {
                                            echo '<option value="' . $row['start_year'] . '-' . $row['end_year'] . '">' . $row['start_year'] . '-' . $row['end_year'] . '</option>';
                                        }

                                        echo '</select>';
                                    }
                                    ?>

                                    <input type="hidden" name="teacher_id" id="teacher_id" value="">
                                </center>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit">Assign</button>
                                </div>
                            </form>

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

                <script>
                    // Attach a click event listener to the Assign buttons
                    $('#assign').on('show.bs.modal', function (event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
                        var teacherId = button.data('teacher-id'); // Extract info from data-* attributes
                        var modal = $(this);
                        modal.find('#teacher_id').val(teacherId); // Set the value of the hidden input
                    });
                </script>



</body>

</html>