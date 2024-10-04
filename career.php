<!-- create_job.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 300px; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; }
        input[type="submit"] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>Create a Job Posting</h1>
    <form action="create_job_action.php" method="POST">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required>

        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" rows="5" required></textarea>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <input type="submit" value="Post Job">
    </form>
</body>


<?php
function PMT($rate, $nper, $pv, $fv = 0, $type = 0) {
    if ($rate != 0) {
        // Payments with an interest rate
        $pmt = ($rate * ($pv * pow(1 + $rate, $nper) + $fv)) / ((1 + $rate * $type) * (pow(1 + $rate, $nper) - 1));
    } else {
        // Payments without an interest rate
        $pmt = ($pv + $fv) / $nper;
    }
    return $pmt;
}

// Example values
$rate = 12/100; // Interest rate per period (D8)
$nper = 10;    // Total number of payment periods (D9)
$pv =  2650000 ;   // Present value (loan amount) (D7)

// Calculate the payment
$payment = PMT($rate, $nper, $pv, 0, 0);

// Round the result to 2 decimal places
$rounded_payment = round($payment, 2);

$interpermonth = $payment / 12;
$intrs = round($interpermonth, 2);

echo "Yearly Amortization :" . $rounded_payment;
echo "<br>";
echo  "Monthly Amortization :". $intrs ;
?>


</html>
