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

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->

                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create a Teacher's Account!</h1>
                    </div>
                    <form class="user" method="post" action="controller/registration.php">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" name="firstname" class="form-control form-control-user"
                                    id="exampleFirstName" placeholder="First Name" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="lastname" class="form-control form-control-user"
                                    id="exampleLastName" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="email" name="email" class="form-control form-control-user" id="Email"
                                    placeholder="Email" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="contact" class="form-control form-control-user"
                                    id="exampleContact" placeholder="Contact Number" required maxlength="11"
                                    oninput="validateInput(this)">
                            </div>

                        </div>
                        <div class="form-group row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <?php
                                include("conn/conn.php");
                                $query = "SELECT * FROM department";
                                $result = mysqli_query($conn, $query);
                                if (!$result) {
                                    echo "Error fetching departments: " . mysqli_error($conn);
                                } else {
                                    echo '<select name="department" class="form-control " id="exampleDepartment" required>';
                                    echo '<option value="" disabled selected>Select Department</option>';
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['id'] . '">' . $row['department_name'] . '</option>';
                                    }
                                    echo '</select>';
                                }
                                ?>
                            </div>

                            <div class="col-sm-6">
                                <input type="text" name="address" class="form-control form-control-user"
                                    id="exampleAddress" placeholder="Address" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" name="password" class="form-control form-control-user"
                                    id="exampleInputPassword" placeholder="Password" required
                                    oninput="validatePassword()">
                            </div>
                            <div class="col-sm-6">
                                <input type="password" name="confirm_password" class="form-control form-control-user"
                                    id="exampleRepeatPassword" placeholder="Repeat Password" required
                                    oninput="validatePassword()">
                            </div>

                            <p id="passwordMessage" style="color: red; display: none;">Password must be 6-8 characters
                                long, contain uppercase, lowercase, and a number.</p>
                            <p id="matchMessage" style="color: red; display: none;">Passwords do not match.</p>
                        </div>
                        <button type="submit" name="save" class="btn btn-primary btn-user btn-block">
                            Register Account
                        </button>
                    </form>
                    <hr>

                    <div class="text-center">
                        <a class="small" href="login.php">Already have an account? Login!</a>
                    </div>
                </div>

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

    <script>
        function validateInput(input) {
            // Remove any non-numeric characters and ensure the length is 11 or less
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
        }
    </script>
    <script>
        function validatePassword() {
            const password = document.getElementById("exampleInputPassword").value;
            const confirmPassword = document.getElementById("exampleRepeatPassword").value;
            const passwordMessage = document.getElementById("passwordMessage");
            const matchMessage = document.getElementById("matchMessage");

            // Regex for 6-8 characters, at least one uppercase letter, one lowercase letter, and one number
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/;

            // Validate password format
            if (!passwordPattern.test(password)) {
                passwordMessage.style.display = "block";
            } else {
                passwordMessage.style.display = "none";
            }

            // Validate passwords match
            if (password && confirmPassword && password !== confirmPassword) {
                matchMessage.style.display = "block";
            } else {
                matchMessage.style.display = "none";
            }
        }
    </script>

    <!-- password and confirm_password -->


</body>

</html>