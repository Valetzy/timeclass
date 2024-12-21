<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 3) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}
include("../conn/conn.php");

$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
$_SESSION['sesion_syear'] = $selected_year;
$year_select = $_SESSION['sesion_syear'];

function displayFirstSemesterSubjectsCount($conn, $year_select) {
    // Base SQL query to count all subjects in the first semester
    $sql = "SELECT COUNT(subjects.semester) AS count 
            FROM assign_subject 
            INNER JOIN subjects 
            ON subjects.s_id = assign_subject.subject_id 
            WHERE subjects.semester = 'first_semester'";

    // If $year_select is not empty, add the condition for the school year
    if (!empty($year_select)) {
        $sql .= " AND subjects.school_year = '$year_select'";
    }
    
    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Fetch the result
    if ($row = mysqli_fetch_assoc($result)) {
        $count = $row['count'];
    } else {
        $count = 0; // Default count if no results are found
    }

    // Display the result in the desired HTML structure
    echo '
    <div class="col-md-5">
        <div class="card bg-primary text-white shadow">
            <div class="card-body" style="font-size: 30px; text-align: center;">
                FirstSem
                <div class="text-white-50 small" style="font-size: 50px;">' . $count . '</div>
            </div>
        </div>
    </div>';
}


function displaySecondSemSubjectsCount($conn, $year_select) {
    // Base SQL query to count all subjects in the first semester
    $sql = "SELECT COUNT(subjects.semester) AS count 
            FROM assign_subject 
            INNER JOIN subjects 
            ON subjects.s_id = assign_subject.subject_id 
            WHERE subjects.semester = 'second_semester'";

    // If $year_select is not empty, add the condition for the school year
    if (!empty($year_select)) {
        $sql .= " AND subjects.school_year = '$year_select'";
    }
    
    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Fetch the result
    if ($row = mysqli_fetch_assoc($result)) {
        $count = $row['count'];
    } else {
        $count = 0; // Default count if no results are found
    }

    // Display the result in the desired HTML structure
    echo '
    <div class="col-md-5">
        <div class="card bg-info text-white shadow">
            <div class="card-body" style="font-size: 30px; text-align: center;">
                SecondSem
                <div class="text-white-50 small" style="font-size: 50px;">' . $count . '</div>
            </div>
        </div>
    </div>';
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

    <!-- Include jQuery library -->
    <script src="vendor/jquery/jquery.min.js"></script>
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lists</h6>
                        </div>
                        <div class="card-body" >

                            <div class="row">

                                <?php displayFirstSemesterSubjectsCount($conn, $year_select ); ?>

                                <form action="" method="post">
                                    
                                    <?php
                                    include('../conn/conn.php');
                                    // Query to select school years from the table
                                    $year_select_query = "SELECT * FROM school_year";
                                    $year_select_result = mysqli_query($conn, $year_select_query);

                                    if (!$year_select_result) {
                                        echo "Error fetching school years: " . mysqli_error($conn);
                                    } else {
                                        echo '<select name="year" id="year" class="form-control" onchange="this.form.submit()">';

                                        // Check if session year is empty
                                        if (empty($_SESSION['sesion_syear'])) {
                                            echo '<option selected disabled value="">School Year</option>';
                                        } else {
                                            echo '<option selected value="">' . $_SESSION['sesion_syear'] . '</option>';
                                        }

                                        echo '<option value="" >All</option>';

                                        // Fetch each row and display as an option
                                        while ($year_select_row = mysqli_fetch_assoc($year_select_result)) {
                                            echo '<option value="' . $year_select_row['start_year'] . '-' . $year_select_row['end_year'] . '">' . $year_select_row['start_year'] . '-' . $year_select_row['end_year'] . '</option>';
                                        }

                                        echo '</select>';
                                    }
                                    ?>
                                </form>

                                <?php displaySecondSemSubjectsCount($conn, $year_select); ?>


                        </div>
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
                                            <th>Assigned Subjects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include("../conn/conn.php");
                                        $sql = "SELECT t.firstname, t.lastname, d.acronym, t.contactnumber, t.t_id FROM teacher AS t INNER JOIN department AS d ON t.department = d.id WHERE confirmation = 1";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["firstname"] . " " . $row["lastname"] ?> </td>
                                                    <td><?php echo $row["acronym"] ?></td>
                                                    <td><?php echo $row["contactnumber"] ?></td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#assign"
                                                            class="btn btn-primary btn-icon-split info-btn"
                                                            data-teacher-id="<?php echo $row["t_id"]; ?>">
                                                            <span class="text">Info</span>
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
                            <h5 class="modal-title" id="exampleModalLabel">Assigned Subjects</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-body">
                            <!-- Subjects will be loaded here dynamically -->
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap core JavaScript-->
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

            <!-- Custom script for handling button click and fetching data -->
            <script>
                $(document).ready(function () {
                    $('.info-btn').on('click', function () {
                        var teacherId = $(this).data('teacher-id');
                        $.ajax({
                            url: 'fetch_subjects.php', // PHP file to handle the data fetching
                            type: 'POST',
                            data: { t_id: teacherId },
                            success: function (response) {
                                $('#modal-body').html(response);
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</body>

</html>