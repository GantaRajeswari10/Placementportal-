<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    // If not logged in, redirect to the login page
    header('Location: homepage.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the student ID from the session
$student_id = $_SESSION['student_id'];

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: ". $database->connect_error);
}

// Prepare SQL statement to delete related records from job_applications table
$sql = "DELETE FROM job_applications WHERE student_id =?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: ". $database->error);
}

// Bind parameters
$statement->bind_param("i", $student_id);

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: ". $statement->error);
}
$statement->close();

// Prepare SQL statement to delete student details from student_details table
$sql = "DELETE FROM student_details WHERE id =$student_id";
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