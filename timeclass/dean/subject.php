<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 3) {
    header("Location: login.php"); // Redirect to login page if not logged in or not a teacher
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
                        <h1 class="h3 mb-0 text-gray-800">Subject's Lists</h1>
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
                                            <th>Course</th>
                                            <th>Year Level</th>
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th>Floor</th>
                                            <th>Room</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Qr_code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        // Query to select all subjects
                                        include("../conn/conn.php");
                                        $sql = "SELECT c.course, c.year_level, c.section, s.subject, s.floor, s.room, s.days, s.start_time, s.end_time, s.qr_code FROM subjects s INNER JOIN courses c ON s.course = c.c_id";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Output data of each row
                                            $index = 0; // Initialize an index to generate unique IDs
                                            while ($row = $result->fetch_assoc()) {
                                                $modal_id = "code_modal_" . $index; // Generate a unique modal ID
                                                $button_id = "button_" . $index; // Generate a unique button ID
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row["course"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["year_level"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["section"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["subject"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["floor"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["room"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["days"]); ?> </td>
                                                    <td><?php echo htmlspecialchars($row["start_time"]) . " - " . htmlspecialchars($row["end_time"]); ?> </td>
                                                    <td>
                                                        <!-- Display QR code image -->
                                                        <button class="btn btn-primary" id="<?php echo $button_id; ?>" data-toggle="modal" data-target="#<?php echo $modal_id; ?>">View Code</button>

                                                        <div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo htmlspecialchars($row["subject"]); ?></h5>
                                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body d-flex justify-content-center align-items-center">
                                                                        <img src="<?php echo htmlspecialchars($row["qr_code"]); ?>" alt="QR Code for <?php echo htmlspecialchars($row["subject"]); ?>" width="300px" >
                                                                        <div class="mt-3" style="font-style: italic;" >
                                                                            <p><?php echo htmlspecialchars($row["course"]). "  " .htmlspecialchars($row["year_level"]). "  " .htmlspecialchars($row["section"]); ?></p>
                                                                            <p><?php echo htmlspecialchars($row["subject"]); ?></p>
                                                                            <p><?php echo htmlspecialchars($row["floor"]); ?></p>
                                                                            <p><?php echo htmlspecialchars($row["room"]); ?></p>
                                                                            <p><?php echo htmlspecialchars($row["days"]); ?></p>
                                                                            <p><?php echo htmlspecialchars($row["start_time"]) . " - " . htmlspecialchars($row["end_time"]); ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $index++; // Increment index for the next modal and button
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No data found</td></tr>";
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
    <script>
        // Function to generate a unique identifier for the QR code
        function generateUniqueIdentifier(subjectDetails) {
            // Example of generating a timestamp as a unique identifier
            return 'qr_code_' + Date.now();
        }

        // Function to update the QR code image with a new unique identifier
        function updateQRCodeImage(subjectDetails) {
            // Generate a unique identifier
            var uniqueIdentifier = generateUniqueIdentifier(subjectDetails);
            
            // Construct the URL to fetch the new QR code image
            var qrCodeImageUrl = 'generate_qr_code.php?unique_id=' + uniqueIdentifier;
            
            // Set the src attribute of the QR code image element
            document.getElementById('qr_code_image').src = qrCodeImageUrl;
        }
    </script>
    <script>
        // Function to handle scanning the QR code
        function handleQRCodeScan(content) {
            // Update the QR code image with a new unique identifier
            updateQRCodeImage(content);
        }
    </script>


</body>

</html>