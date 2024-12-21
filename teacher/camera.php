<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 1) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}

// Include your database connection file
include('../conn/conn.php');

$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
$_SESSION['sesion_syear'] = $selected_year;
// Fetch teacher_id from session
$t_id = $_SESSION['t_id'];

// Base query
$query = "SELECT ass.school_year, s.floor, s.s_id, c.c_id, s.subject, c.course, c.year_level, s.room, s.days, s.start_time, s.end_time FROM assign_subject AS ass
        INNER JOIN subjects AS s ON ass.subject_id = s.s_id
        INNER JOIN courses AS c ON ass.course_id = c.c_id 
        WHERE teacher_id = ?";

// If year is specified, add the condition for school_year
if (!empty($selected_year)) {
    $query .= " AND ass.school_year = ?";
}

// Prepare the SQL statement
$stmt = $conn->prepare($query);

// Bind parameters
if (!empty($selected_year)) {
    $stmt->bind_param("ss", $t_id, $selected_year);
} else {
    $stmt->bind_param("s", $t_id);
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

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
    <!-- Include Html5Qrcode Library -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <style>
        /* Center the video container in the modal */
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            width: 90%;
            max-width: 600px;
            /* Adjust as needed */
        }

        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .scanner-con {
            text-align: center;
        }

        video#interactive {
            width: 100%;
            /* Adjust as needed */
            max-width: 400px;
            /* Adjust as needed */
        }
    </style>
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
                        <h1 class="h3 mb-0 text-gray-800">My Dashboard</h1>
                        <form action="" method="post">
                            <?php
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
                    </div>
                    <div class="row">
                        <?php
                        // Check if any data is returned
                        if (mysqli_num_rows($result) > 0) {
                            // Fetch data and display it
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Display card with data from the database
                                echo '
                                <div class="card shadow mb-4 col-md-3" style="margin: 30px;" >
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">' . htmlspecialchars($row['subject']) . '</h6>
                                    </div>
                                    <div class="card-body">
                                        <p> <b>Course: </b>' . htmlspecialchars($row['course']) . '</p>
                                        <p> <b>Year Level: </b>' . htmlspecialchars($row['year_level']) . '</p>
                                        <p> <b>Room: </b>' . htmlspecialchars($row['room']) . '</p>
                                        <p> <b>Days: </b>' . htmlspecialchars($row['days']) . '</p>
                                        <p> <b>Time: </b>' . htmlspecialchars($row['start_time']) . ' - ' . htmlspecialchars($row['end_time']) . '</p>
                                        <div style="display: flex; " >
                                        <button class="btn btn-primary btn-icon-split" style="font-size: 10px" data-toggle="modal" data-target="#openscanner" data-s_id="' . htmlspecialchars($row['s_id']) . '" data-subject="' . htmlspecialchars($row['subject']) . '" data-c_id="' . htmlspecialchars($row['c_id']) . '" data-start_time="' . htmlspecialchars($row['start_time']) . '" data-end_time="' . htmlspecialchars($row['end_time']) . '" data-room="' . htmlspecialchars($row['room']) . '" data-floor="' . htmlspecialchars($row['floor']) . '">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-camera"></i>
                                            </span>
                                            <span class="text">Open Scanner</span>
                                        </button>
                                        <button class="btn btn-primary btn-icon-split" style="margin-left: 20px; font-size: 10px;" data-toggle="modal" data-target="#manual" 
                                            data-s_id="' . htmlspecialchars($row['s_id']) . '" 
                                            data-subject="' . htmlspecialchars($row['subject']) . '" 
                                            data-c_id="' . htmlspecialchars($row['c_id']) . '" 
                                            data-start_time="' . htmlspecialchars($row['start_time']) . '" 
                                            data-end_time="' . htmlspecialchars($row['end_time']) . '" 
                                            data-room="' . htmlspecialchars($row['room']) . '" 
                                            data-floor="' . htmlspecialchars($row['floor']) . '">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-book"></i>
                                            </span>
                                            <span class="text">Manual Attendance</span>
                                        </button>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<div class="alert alert-warning" role="alert">No subjects assigned.</div>';
                        }
                        // Close the database connection
                        mysqli_close($conn);
                        ?>
                    </div>
                    <!-- Scanner Modal -->
                    <div class="modal fade" id="openscanner" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Subject</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="qr-container">
                                        <div class="scanner-con">
                                            <video id="interactive" class="viewport"></video>
                                            <div class="qr-detected-container" style="display: none;">
                                                <!-- Form for submitting attendance -->
                                                <form id="attendance-form" action="capture_selfie.php" method="POST">
                                                    <h4 class="text-center">Teacher QR Detected!</h4>
                                                    <input type="hidden" id="detected-qr-code" name="data">
                                                    <input type="hidden" id="s_id" name="s_id">
                                                    <input type="hidden" id="subject" name="subject">
                                                    <input type="hidden" id="c_id" name="c_id">
                                                    <input type="hidden" id="start_time" name="start_time">
                                                    <!-- Added this line -->
                                                    <input type="hidden" id="end_time" name="end_time">
                                                    <!-- Added this line -->
                                                    <input type="hidden" id="t_id" name="t_id"
                                                        value="<?php echo $_SESSION['t_id']; ?>">
                                                    <input type="hidden" id="room" name="room"> <!-- Added this line -->
                                                    <input type="hidden" id="floor" name="floor">
                                                    <!-- Added this line -->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Logout Modal-->
                    <div class="modal fade" id="manual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <form action="capture_selfie.php" method="POST">
                                    <div class="modal-body">

                                        <div class="col-sm-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea type="text" class="form-control form-control-user"
                                                name="description" id="description"></textarea>
                                        </div>
                                        <input type="hidden" id="s_id" name="s_id">
                                        <input type="hidden" id="subject" name="subject">
                                        <input type="hidden" id="c_id" name="c_id">
                                        <input type="hidden" id="start_time" name="start_time">
                                        <input type="hidden" id="end_time" name="end_time">
                                        <input type="hidden" id="t_id" name="t_id"
                                            value="<?php echo $_SESSION['t_id']; ?>">
                                        <input type="hidden" id="room" name="room">
                                        <input type="hidden" id="floor" name="floor">

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-dismiss="modal">Cancel</button>
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php include("navigations/footer.php") ?>
                </div>
                <!-- End of Content Wrapper -->
            </div>
            <!-- End of Page Wrapper -->
            <!-- Scroll to Top Button -->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
            <?php include "navigations/logout.php"; ?>
            <!-- Place JavaScript at the end of the body to ensure the library is loaded -->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="js/sb-admin-2.min.js"></script>
            <script src="vendor/chart.js/Chart.min.js"></script>
            <script src="js/demo/chart-area-demo.js"></script>
            <script src="js/demo/chart-pie-demo.js"></script>
            <!-- Instascan Js -->
            <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
            <script>
                let scanner;

                function startScanner() {
                    scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

                    scanner.addListener('scan', function (content) {
                        $("#detected-qr-code").val(content);
                        console.log(content);
                        scanner.stop();
                        document.querySelector(".qr-detected-container").style.display = "block";
                        document.querySelector(".scanner-con video").style.display = "none";
                        document.getElementById('attendance-form').submit();
                    });

                    Instascan.Camera.getCameras()
                        .then(function (cameras) {
                            if (cameras.length > 0) {
                                let backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back')) || cameras[0];
                                scanner.start(backCamera);
                            } else {
                                console.error('No cameras found.');
                                alert('No cameras found.');
                            }
                        })
                        .catch(function (err) {
                            console.error('Camera access error:', err);
                            alert('Camera access error: ' + err);
                        });
                }

                function stopScanner() {
                    if (scanner) {
                        scanner.stop();
                    }
                }

                $(document).ready(function () {
                    $('#openscanner').on('show.bs.modal', function (event) {
                        startScanner(); // Start the camera when the modal is shown
                        var button = $(event.relatedTarget); // Button that triggered the modal
                        var s_id = button.data('s_id'); // Extract info from data-* attributes
                        var subject = button.data('subject'); // Extract info from data-* attributes
                        var c_id = button.data('c_id'); // Extract info from data-* attributes
                        var start_time = button.data('start_time'); // Extract info from data-* attributes
                        var end_time = button.data('end_time'); // Extract info from data-* attributes
                        var room = button.data('room'); // Extract info from data-* attributes
                        var floor = button.data('floor'); // Extract info from data-* attributes

                        var modal = $(this);
                        modal.find('#s_id').val(s_id);
                        modal.find('#subject').val(subject);
                        modal.find('#c_id').val(c_id);
                        modal.find('#start_time').val(start_time); // Set the start_time value
                        modal.find('#end_time').val(end_time); // Set the end_time value
                        modal.find('#room').val(room); // Set the room value
                        modal.find('#floor').val(floor); // Set the floor value
                    });

                    $('#openscanner').on('hidden.bs.modal', function (e) {
                        stopScanner(); // Stop the camera when the modal is hidden
                    });
                });

                function deleteAttendance(id) {
                    if (confirm("Do you want to remove this attendance?")) {
                        window.location = "./endpoint/delete-attendance.php?attendance=" + id;
                    }
                }

                $(document).ready(function () {
                    // Handle opening of the Manual Attendance modal
                    $('#manual').on('show.bs.modal', function (event) {
                        // Get the button that triggered the modal
                        var button = $(event.relatedTarget);

                        // Extract data attributes
                        var s_id = button.data('s_id');
                        var subject = button.data('subject');
                        var c_id = button.data('c_id');
                        var start_time = button.data('start_time');
                        var end_time = button.data('end_time');
                        var room = button.data('room');
                        var floor = button.data('floor');

                        // Populate the form fields in the modal
                        var modal = $(this);
                        modal.find('#s_id').val(s_id);
                        modal.find('#subject').val(subject);
                        modal.find('#c_id').val(c_id);
                        modal.find('#start_time').val(start_time);
                        modal.find('#end_time').val(end_time);
                        modal.find('#room').val(room);
                        modal.find('#floor').val(floor);
                    });
                });

            </script>
</body>

</html>