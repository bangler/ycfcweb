<!-- update_job.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Job</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 300px; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; }
        input[type="submit"] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>Update Job Posting</h1>
    <?php
    $conn = new mysqli("localhost", "root", "", "ycfc-web");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $job_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM jobs WHERE id = $job_id");

    if ($row = $result->fetch_assoc()) {
    ?>
        <form action="update_job_action.php" method="POST">
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo $row['job_title']; ?>" required>

            <label for="job_description">Job Description:</label>
            <textarea id="job_description" name="job_description" rows="5" required><?php echo $row['job_description']; ?></textarea>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo $row['location']; ?>" required>

            <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
            <input type="submit" value="Update Job">
        </form>
    <?php
    } else {
        echo "Job not found.";
    }

    $conn->close();
    ?>
</body>
</html>
