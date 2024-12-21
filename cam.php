<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dual Camera Viewer</title>
    <style>
        body {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        video {
            width: 45%;
            height: auto;
            border: 2px solid black;
        }
    </style>
</head>

<body>

    <video id="frontCamera" autoplay playsinline></video>
    <video id="backCamera" autoplay playsinline></video>

    <script>
        async function startCameras() {
            const frontConstraints = {
                video: {
                    facingMode: 'user' // Front camera
                }
            };

            const backConstraints = {
                video: {
                    facingMode: 'environment' // Back camera
                }
            };

            try {
                // Access front camera
                const frontStream = await navigator.mediaDevices.getUserMedia(frontConstraints);
                const frontVideo = document.getElementById('frontCamera');
                frontVideo.srcObject = frontStream;

                // Access back camera
                const backStream = await navigator.mediaDevices.getUserMedia(backConstraints);
                const backVideo = document.getElementById('backCamera');
                backVideo.srcObject = backStream;

            } catch (error) {
                console.error('Error accessing the cameras: ', error);
            }
        }

        // Initialize the cameras
        startCameras();

    </script>
</body>

</html>