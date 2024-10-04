<?php
include 'dbcon/db.php';

// Function to add new property
function addProperty($conn, $branch_id, $location, $description, $tct_no, $area) {
    $sql = "INSERT INTO tbl_property (branch_id, location, description, tct_no, area) VALUES ('$branch_id', '$location', '$description', '$tct_no', '$area')";
    if ($conn->query($sql) === TRUE) {
        return $conn->insert_id; // Return the inserted property ID
    }
    return false;
}

// Function to handle image upload
function uploadImages($conn, $property_id, $images) {
    $total_images = count($images['name']);
    $upload_dir = 'uploads/';
    
    if ($total_images > 0 && $total_images <= 5) {
        for ($i = 0; $i < $total_images; $i++) {
            $image_name = $images['name'][$i];
            $image_tmp = $images['tmp_name'][$i];
            $image_path = $upload_dir . basename($image_name);

            if (move_uploaded_file($image_tmp, $image_path)) {
                // Insert image path into the tbl_images table
                $conn->query("INSERT INTO tbl_images (property_id, image_path) VALUES ('$property_id', '$image_path')");
            }
        }
    }
}

// Handling form submission
if (isset($_POST['submit'])) {
    $branch_id = $_POST['branch_id'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $tct_no = $_POST['tct_no'];
    $area = $_POST['area'];

    $property_id = addProperty($conn, $branch_id, $location, $description, $tct_no, $area);

    if ($property_id) {
        uploadImages($conn, $property_id, $_FILES['property_images']);
        header("Location: index.php");
    } else {
        echo "Error adding property.";
    }
}

// Function to delete property
if (isset($_GET['delete'])) {
    $property_id = $_GET['delete'];
    $conn->query("DELETE FROM tbl_property WHERE id = $property_id");
    header("Location: index.php");
}
?>

<!-- Form for property submission -->
<form method="POST" action="" enctype="multipart/form-data">
    <label for="branch">Branch:</label>
    <select name="branch_id">
        <?php
        $branches = $conn->query("SELECT * FROM tbl_branch");
        while ($row = $branches->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['branch_name'] . "</option>";
        }
        ?>
    </select><br>

    <label for="location">Location:</label>
    <input type="text" name="location" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea><br>

    <label for="tct_no">TCT No:</label>
    <input type="text" name="tct_no" required><br>

    <label for="area">Area (sqm):</label>
    <input type="text" name="area" required><br>

    <label for="property_images">Upload Images (Max 5):</label>
    <input type="file" name="property_images[]" accept="image/*" multiple required><br>

    <input type="submit" name="submit" value="Create">
</form>

<!-- Table to display and manage properties -->
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Branch</th>
            <th>Location</th>
            <th>Description</th>
            <th>TCT No</th>
            <th>Area (sqm)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT p.*, b.branch_name FROM tbl_property p JOIN tbl_branch b ON p.branch_id = b.id");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['tct_no'] . "</td>";
            echo "<td>" . $row['area'] . " sqm</td>";
            echo "<td>
                <a href='update_property.php?id=" . $row['id'] . "'>Update</a> |
                <a href='?delete=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this property?');\">Delete</a>
            </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Updating a property would go to update_property.php which will handle the updating logic
?>
