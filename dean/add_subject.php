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
                        <h1 class="h3 mb-0 text-gray-800">Add Subjects</h1>
                    </div>

                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Add Subject!</h1>
                                </div>
                                <form action="../controller/add_subject.php" class="user" method="post">
                                    <div class="form-group row">
                                        <div class="form-control-user mb-3 col-sm-3" style="padding: 20px; border-radius: 10px;">
                                            <select class="form-control" name="courses">
                                                <option value="option1" selected disabled>Courses</option>
                                                <?php
                                                include("../conn/conn.php");
                                                $sql = "SELECT * FROM courses";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    // Output data of each row
                                                    while ($row = $result->fetch_assoc()) {
                                                ?>
                                                        <option value="<?php echo $row["c_id"] ?>"><?php echo $row["course"] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <!-- Add more options if needed -->
                                            </select>
                                        </div>
                                        <div class="form-control-user mb-3 col-sm-3" style="padding: 20px; border-radius: 10px;">
                                            <select class="form-control" name="floor">
                                                <option value="option1" selected disabled>Floors</option>
                                                <option value="1st">1st</option>
                                                <option value="2nd">2nd</option>
                                                <option value="3rd">3rd</option>
                                                <option value="4th">4th</option>
                                                <option value="5th">5th</option>
                                                <option value="6th">6th</option>
                                                <option value="7th">7th</option>
                                                <option value="8th">8th</option>
                                                <option value="Gym">Gym</option>
                                                <!-- Add more options if needed -->
                                            </select>
                                        </div>

                                        <div class="form-control-user mb-3 col-sm-3"  style="padding: 20px; border-radius: 10px;">
                                            <?php
                                            // Query to select school years from the table
                                            $query = "SELECT * FROM school_year";
                                            $result = mysqli_query($conn, $query);

                                            if (!$result) {
                                                echo "Error fetching school years: " . mysqli_error($conn);
                                            } else {
                                                echo '<select class="form-control" aria-label="Default select example" name="school_year" >';
                                                echo '<option selected>School Year</option>';

                                                // Fetch each row and display as an option
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['start_year'] . '-' . $row['end_year'] . '">' . $row['start_year'] . '-' . $row['end_year'] . '</option>';
                                                }

                                                echo '</select>';
                                            }
                                            ?>
                                        </div>

                                        <div class="form-control-user mb-3 col-sm-3" style="padding: 20px; border-radius: 10px;">
                                            <select class="form-control" name="semester">
                                                <option value="" selected disabled>Semester</option>
                                                <option value="first_semester">First Semester</option>
                                                <option value="second_semester">Second Semester</option>
                                                <!-- Add more options if needed -->
                                            </select>
                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <input type="text" class="form-control form-control-user" name="subject" id="exampleSubject" placeholder="Subject">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <input type="text" class="form-control form-control-user" name="room" id="exampleRoom" placeholder="Room">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label for="exampleDays">Select Days:</label><br>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="monday" name="days[]" value="M">
                                                <label for="monday" class="form-check-label">Monday</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="tuesday" name="days[]" value="T">
                                                <label for="tuesday" class="form-check-label">Tuesday</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="wednesday" name="days[]" value="W">
                                                <label for="wednesday" class="form-check-label">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label for="exampleDays">&nbsp;</label><br>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="thursday" name="days[]" value="Th">
                                                <label for="thursday" class="form-check-label">Thursday</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="friday" name="days[]" value="F">
                                                <label for="friday" class="form-check-label">Friday</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="saturday" name="days[]" value="Sat">
                                                <label for="saturday" class="form-check-label">Saturday</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="sunday" name="days[]" value="Sun">
                                                <label for="sunday" class="form-check-label">Sunday</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label for="exampleStartTime">Start Class Time:</label>
                                            <input type="time" class="form-control form-control-user" id="exampleStartTime" name="start_time">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label for="exampleEndTime">End Class Time:</label>
                                            <input type="time" class="form-control form-control-user" id="exampleEndTime" name="end_time">
                                        </div>

                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button style="margin-right: 15px;" type="submit" class="btn btn-info">Submit</button>
                                        <button type="button" class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>


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
                                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
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