<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "timeclass";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the image data from the POST request
$data = json_decode(file_get_contents("php://input"));

if(isset($data->image)) {
    $image = $data->image;

    // Remove the "data:image/png;base64," part
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);

    // Decode the image
    $imageData = base64_decode($image);

    // Create a unique filename and save to the server
    $fileName = 'captured_' . time() . '.png';
    $filePath = 'uploads/' . $fileName;
    
    // Save the file
    file_put_contents($filePath, $imageData);

    // Insert the file path into the database
    $sql = "INSERT INTO images (file_path) VALUES ('$filePath')";

    if ($conn->query($sql) === TRUE) {
        echo "Image saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No image data received.";
}

$conn->close();
?>
