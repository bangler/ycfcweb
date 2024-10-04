<!-- read_jobs.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .job { margin-bottom: 20px; }
        a { text-decoration: none; color: #2196F3; margin-right: 10px; }
        a:hover { text-decoration: underline; }
        h2 { margin-bottom: 5px; }
        hr { border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Job Postings</h1>
    <a href="career.php">Create New Job</a>
    <hr>
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "ycfc-web");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='job'>";
            echo "<h2>" . $row['job_title'] . "</h2>";
            echo "<textarea rows='5' disable>" . $row['job_description'] . "</textarea>";
            echo "<p>Location: " . $row['location'] . "</p>";
            echo "<p>Posted on: " . $row['created_at'] . "</p>";
            echo "<a href='update_job.php?id=" . $row['id'] . "'>Edit</a>";
            echo "<a href='delete_job.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this job?\")'>Delete</a>";
            echo "</div>";
            echo "<hr>";
        }
    } else {
        echo "No job postings available.";
    }

    $conn->close();
    ?>
</body>
</html>
