<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    // If not logged in, redirect to the login page
    header('Location: student_login.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the username from the session
$firstname = $_SESSION['firstname'];

// Fetch the student details from the database
$database = new mysqli('localhost', 'root', '', 'placement');
$statement = $database->prepare("SELECT * FROM student_details WHERE firstname =?");
$statement->bind_param("s", $firstname);
$statement->execute();
$result = $statement->get_result();
$student = $result->fetch_assoc();

// Update the student details in the database
$statement = $database->prepare("UPDATE student_details SET firstname=?, lastname=?, email=?, gender=?, phone=?, address=? WHERE firstname=?");
$statement->bind_param("sssssss", $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['gender'], $_POST['phone'], $_POST['address'], $firstname);
$statement->execute();

// Redirect the user to their updated profile page
header('Location: student_myprofile.php');
exit;
?>