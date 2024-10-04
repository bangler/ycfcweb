<?php include 'body/header.php';?>
  <link rel="stylesheet" >
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <div class="container">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt40">
            <div class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="index.html">Home</a></li>
                    <li class="active text-light">Properties For Sale</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="section-space20">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="bg-white pinside30">
                    <div class="form-row">
                        <div class="col-4">
                            <h1>PROPERTIES <strong>FOR SALE</strong></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pdb40 compare-table">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="bg-white table-responsive">

                <?php
                            include 'dbcon/db.php';

                            $result = $conn->query("SELECT p.*, b.branch_name FROM tbl_property p JOIN tbl_branch b ON p.branch_id = b.id ORDER BY b.branch_name, p.location");

                            $currentBranch = ''; // Initialize variable to track the current branch

                            while ($row = $result->fetch_assoc()) {
                                // If branch name changes, output a new section for that branch
                                if ($currentBranch != $row['branch_name']) {
                                    if ($currentBranch != '') {
                                        // Close the previous branch's table
                                        echo '</tbody></table>';
                                    }

                                    // Set the new current branch
                                    $currentBranch = $row['branch_name'];

                                    // Output the new branch name as a heading
                                    echo '<div class="section-space20">';
                                    echo '<h2>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $currentBranch . ' Branch</h2>';
                                    echo '</div>';
                                    echo '<table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width:40%;" class="card-name">Location</th>
                                                    <th style="width:20%;" class="card-tag">Description</th>
                                                    <th style="width:11%;" class="anuual-fees">TCT No.</th>
                                                    <th class="reward-rate">Area</th>
                                                    <th class="action">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                }

                                // Output the property details
                                echo "<tr>";
                                echo "<td><span class='text-dark compare-card-title'>{$row['location']}</span></td>";
                                echo "<td><span class='text-dark compare-card-title'>{$row['description']}</span></td>";
                                echo "<td class='text-dark text-bold'>{$row['tct_no']}</td>";
                                echo "<td class='text-dark text-bold'>{$row['area']} sqm</td>";
                                echo "<td class='text-center'>";
                                echo '<a href="#" class="btn btn-default btn-sm mb5" data-toggle="modal" data-target="#imagesModal' . $row['id'] . '">See Images</a>';
                                echo "</td>";
                                echo "</tr>";

                                // Fetch the first image for the property
                                $firstImageQuery = $conn->query("SELECT * FROM tbl_images WHERE property_id = {$row['id']} LIMIT 1");
                                $firstImage = $firstImageQuery->fetch_assoc();
                                $firstImagePath = $firstImage ? $firstImage['image_path'] : 'images/placeholder.jpg';  // Fallback to placeholder if no image

                                // Modal for displaying property images
                                echo '<div class="modal fade" id="imagesModal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Property Images</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="column1">
                                                            <img id="expandedImg' . $row['id'] . '" src="' . $firstImagePath . '" style="width:100%">
                                                            <div id="imgtext' . $row['id'] . '"></div>
                                                        </div>
                                                        <div class="column2">';

                                // Fetch the remaining images for the property
                                $images = $conn->query("SELECT * FROM tbl_images WHERE property_id = {$row['id']}");
                                while ($image = $images->fetch_assoc()) {
                                    echo "<img src='" . $image['image_path'] . "' alt='Property Image' style='width:80%' onclick='myFunction(this, " . $row['id'] . ");'><br><br>";
                                }

                                echo '          </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }

                            // Close the final branch's table
                            if ($currentBranch != '') {
                                echo '</tbody></table>';
                            }
                            ?>


                </div>
            </div>
        </div>
    </div>
</div>

<style>
* {
    box-sizing: border-box;
}

/* Create two equal columns that float next to each other */
.column1 {
    float: left;
    width: 80%;
    padding: 10px;
}

.column2 {
    float: left;
    width: 20%;
    padding: 10px;
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style>

<script>
function myFunction(imgs, propertyId) {
    var expandImg = document.getElementById("expandedImg" + propertyId);
    var imgText = document.getElementById("imgtext" + propertyId);
    expandImg.src = imgs.src;
    imgText.innerHTML = imgs.alt;
    expandImg.parentElement.style.display = "block";
}
</script>

<?php include 'body/footer.php';?>