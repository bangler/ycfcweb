<?php
include 'dbcon/db.php';

// Fetch property details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $property = $conn->query("SELECT * FROM tbl_property WHERE id = $id")->fetch_assoc();
}

// Handle property and image update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $branch_id = $_POST['branch_id'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $tct_no = $_POST['tct_no'];
    $area = $_POST['area'];

    // Update property details
    $sql = "UPDATE tbl_property SET branch_id='$branch_id', location='$location', description='$description', tct_no='$tct_no', area='$area' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {

        // Handle new image uploads (if any)
        if (isset($_FILES['new_property_images']) && $_FILES['new_property_images']['name'][0] != '') {
            $total_images = count($_FILES['new_property_images']['name']);
            $upload_dir = 'uploads/';

            if ($total_images > 0 && $total_images <= 5) {
                for ($i = 0; $i < $total_images; $i++) {
                    $image_name = $_FILES['new_property_images']['name'][$i];
                    $image_tmp = $_FILES['new_property_images']['tmp_name'][$i];
                    $image_path = $upload_dir . basename($image_name);

                    if (move_uploaded_file($image_tmp, $image_path)) {
                        // Insert new image path into tbl_images
                        $conn->query("INSERT INTO tbl_images (property_id, image_path) VALUES ('$id', '$image_path')");
                    }
                }
            }
        }
        
        header("Location: index.php");
    } else {
        echo "Error updating property.";
    }
}

// Handle image deletion
if (isset($_GET['delete_image'])) {
    $image_id = $_GET['delete_image'];
    // Fetch the image path before deleting it
    $image = $conn->query("SELECT image_path FROM tbl_images WHERE id = $image_id")->fetch_assoc();
    $image_path = $image['image_path'];

    // Delete the image record from the database
    $conn->query("DELETE FROM tbl_images WHERE id = $image_id");

    // Delete the actual file from the uploads directory
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    header("Location: update_property.php?id=$id");
}


?>

<!-- Update Property Form -->
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $property['id']; ?>">

    <label for="branch">Branch:</label>
    <select name="branch_id">
        <?php
        $branches = $conn->query("SELECT * FROM tbl_branch");
        while ($row = $branches->fetch_assoc()) {
            $selected = ($property['branch_id'] == $row['id']) ? 'selected' : '';
            echo "<option value='" . $row['id'] . "' $selected>" . $row['branch_name'] . "</option>";
        }
        ?>
    </select><br>

    <label for="location">Location:</label>
    <input type="text" name="location" value="<?php echo $property['location']; ?>" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required><?php echo $property['description']; ?></textarea><br>

    <label for="tct_no">TCT No:</label>
    <input type="text" name="tct_no" value="<?php echo $property['tct_no']; ?>" required><br>

    <label for="area">Area (sqm):</label>
    <input type="text" name="area" value="<?php echo $property['area']; ?>" required><br>

    <!-- Display current images with option to delete -->
    <label for="current_images">Current Images:</label><br>
    <?php
    $images = $conn->query("SELECT * FROM tbl_images WHERE property_id = $id");
    while ($image = $images->fetch_assoc()) {
        echo "<div style='display: inline-block; margin-right: 10px;'>";
        echo "<img src='" . $image['image_path'] . "' class='img-thumbnail' style='width:100px;'><br>";
        echo "<a href='update_property.php?id=$id&delete_image=" . $image['id'] . "' onclick=\"return confirm('Are you sure you want to delete this image?');\">Delete</a>";
        echo "</div>";
    }
    ?><br>

    <!-- New image upload option -->
    <label for="new_property_images">Upload New Images (Max 5):</label>
    <input type="file" name="new_property_images[]" accept="image/*" multiple><br>

    <input type="submit" name="update" value="Update Property">
</form>

