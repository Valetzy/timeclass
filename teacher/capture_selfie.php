<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 1) {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not a teacher
    exit();
}
include('../conn/conn.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selfie Camera</title>
    <style>
        body {
            background-color: #212121;
        }

        h1 {
            display: flex;
            justify-content: center;
            color: cornflowerblue;

        }

        p {
            display: flex;
            justify-content: center;
            color: Red;
        }

        /* From Uiverse.io by gharsh11032000 */
        .animated-button {
            position: relative;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 16px 36px;
            border: 4px solid;
            border-color: transparent;
            font-size: 16px;
            background-color: inherit;
            border-radius: 100px;
            font-weight: 600;
            color: greenyellow;
            box-shadow: 0 0 0 2px greenyellow;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .animated-button svg {
            position: absolute;
            width: 24px;
            fill: greenyellow;
            z-index: 9;
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .animated-button .arr-1 {
            right: 16px;
        }

        .animated-button .arr-2 {
            left: -25%;
        }

        .animated-button .circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background-color: greenyellow;
            border-radius: 50%;
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .animated-button .text {
            position: relative;
            z-index: 1;
            transform: translateX(-12px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .animated-button:hover {
            box-shadow: 0 0 0 12px transparent;
            color: #212121;
            border-radius: 12px;
        }

        .animated-button:hover .arr-1 {
            right: -25%;
        }

        .animated-button:hover .arr-2 {
            left: 16px;
        }

        .animated-button:hover .text {
            transform: translateX(12px);
        }

        .animated-button:hover svg {
            fill: #212121;
        }

        .animated-button:active {
            scale: 0.95;
            box-shadow: 0 0 0 4px greenyellow;
        }

        .animated-button:hover .circle {
            width: 220px;
            height: 220px;
            opacity: 1;
        }
    </style>
</head>

<body>

    <h1>Take a Selfie</h1>
    <p>Capture a Selfie in the Classroom</p>
    <p>Not following the Rules will be Punished!</p>
    <video id="video" width="100%" height="425" autoplay></video>
    <center>
        <button id="capture" class="animated-button">

            <span class="text">Capture Photo</span>
            <span class="circle"></span>

        </button>
    </center>

    <canvas id="canvas" width="400" height="350" style="display:none;"></canvas>

    <script>
        // Access camera
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(error => console.error('Camera access denied:', error));

        // Capture photo
        const captureButton = document.getElementById('capture');
        captureButton.addEventListener('click', () => {
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png'); // base64 data URL
            savePhoto(imageData);
        });

        // Function to send photo and attendance data to PHP script
        function savePhoto(imageData) {
            const formData = new FormData();
            formData.append('image', imageData);
            formData.append('data', '<?= $_POST['data'] ?>'); // Sending scanned data
            formData.append('s_id', '<?= $_POST['s_id'] ?>'); // Sending student ID
            formData.append('subject', '<?= $_POST['subject'] ?>'); // Sending subject
            formData.append('c_id', '<?= $_POST['c_id'] ?>'); // Sending class ID
            formData.append('t_id', '<?= $_POST['t_id'] ?>'); // Sending teacher ID
            formData.append('start_time', '<?= $_POST['start_time'] ?>'); // Sending start time
            formData.append('end_time', '<?= $_POST['end_time'] ?>'); // Sending end time
            formData.append('room', '<?= $_POST['room'] ?>'); // Sending room
            formData.append('floor', '<?= $_POST['floor'] ?>'); // Sending floor

            fetch('save_attendance_test.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display server response
                    // Navigate back to teacher_db.php after capturing photo
                    window.location.href = 'teacher_db.php';
                })
                .catch(error => console.error('Error saving photo:', error));
        }
    </script>

</body>

</html>