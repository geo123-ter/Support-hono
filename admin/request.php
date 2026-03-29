<?php

include('db.php');

?><?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    $household_members = $_POST['household_members'];
    $children = $_POST['children'];
    $elderly = $_POST['elderly'];
    $head = $_POST['head_of_household'];

    $employment = $_POST['employment_status'];
    $income = $_POST['monthly_income'];
    $income_source = $_POST['income_source'];
    $other_support = $_POST['other_support'];

    // checkbox array → convert to string
    $support_type = "";
    if (isset($_POST['support_type'])) {
        $support_type = implode(", ", $_POST['support_type']);
    }

    $other_support_type = $_POST['other_support_type'];
    $reason = $_POST['reason'];

    // INSERT QUERY
    $sql = "INSERT INTO requests (
        full_name, phone, email, dob, gender, address,
        household_members, children, elderly, head_of_household,
        employment_status, monthly_income, income_source, other_support,
        support_type, other_support_type, reason
    ) VALUES (
        '$full_name', '$phone', '$email', '$dob', '$gender', '$address',
        '$household_members', '$children', '$elderly', '$head',
        '$employment', '$income', '$income_source', '$other_support',
        '$support_type', '$other_support_type', '$reason'
    )";

    if (mysqli_query($conn, $sql)) {
        echo "✅ Request submitted successfully!";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>