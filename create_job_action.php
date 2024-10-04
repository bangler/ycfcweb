<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $location = $_POST['location'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "ycfc-web");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert job into the database
    $stmt = $conn->prepare("INSERT INTO jobs (job_title, job_description, location, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $job_title, $job_description, $location);

    if ($stmt->execute()) {
        echo "Job posted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
