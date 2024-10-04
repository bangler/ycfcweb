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