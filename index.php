<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .splash-text {
            text-align: center;
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="splash-text">
        <h1>Welcome to Timeclass</h1>
        <p>Loading...</p>
    </div>

    <script>
        // Redirect to login page after 3 seconds
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 3000); // 3000 milliseconds = 3 seconds
    </script>
</body>
</html>
