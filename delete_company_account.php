<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['companyname'])) {
    // If not logged in, redirect to the login page
    header('Location: company_login.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the company ID from the session
$company_id = $_SESSION['company_id'];

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: ". $database->connect_error);
}

// Prepare SQL statement to delete related records from vacancies table
$sql = "DELETE FROM vacancies WHERE company_id =?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: ". $database->error);
}

// Bind parameters
$statement->bind_param("i", $company_id);

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: ". $statement->error);
}
$statement->close();

// Prepare SQL statement to delete company details from company_details table
$sql = "DELETE FROM company_details WHERE id =$company_id";
$statement = $database->query($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: ". $database->error);
}

// Close the statement and database connection

$database->close();

// Redirect to the homepage
header('Location: homepage.html');
exit;
?>