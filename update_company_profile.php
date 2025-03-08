<?php
session_start();

// Check if the company is logged in
if (!isset($_SESSION['companyname'])) {
    // If not logged in, redirect to the login page
    header('Location: company_login.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the company name from the session
$companyname = $_SESSION['companyname'];

// Fetch the company details from the database
$database = new mysqli('localhost', 'root', '', 'placement');
$statement = $database->prepare("SELECT * FROM company_details WHERE companyname =?");
$statement->bind_param("s", $companyname);
$statement->execute();
$result = $statement->get_result();
$company = $result->fetch_assoc();

// Update the company details in the database
$statement = $database->prepare("UPDATE company_details SET companyname=?, email=?, industry=?, address=? WHERE companyname=?");
$statement->bind_param("sssss", $_POST['companyname'], $_POST['email'], $_POST['industry'], $_POST['address'], $companyname);
$statement->execute();

// Redirect the user to their updated profile page
header('Location: company_myprofile.php');
exit;
?>