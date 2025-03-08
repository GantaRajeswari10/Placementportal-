<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // If not logged in, redirect to the login page or wherever appropriate
    header('Location: login.php');
    exit;
}

// Check if job_id is provided
if (!isset($_GET['job_id'])) {
    // Redirect back if job_id is not provided
    header('Location: apply_job.php');
    exit;
}

// Connect to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Get student ID and job ID from session and URL parameters
$studentId = $_SESSION['student_id'];
$jobId = $_GET['job_id'];

// Retrieve resume information from the database using student ID and job ID
$query = "SELECT resume_name, resume FROM job_applications WHERE student_id = ? AND job_id = ?";
$statement = $database->prepare($query);

if (!$statement) {
    die("Error preparing statement: " . $database->error);
}

$statement->bind_param("ii", $studentId, $jobId);
$statement->execute();
$statement->store_result();

if ($statement->num_rows == 1) {
    $statement->bind_result($resumeName, $resumeContent);
    $statement->fetch();

    // Set the appropriate headers for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $resumeName . '"');
    header('Content-Length: ' . strlen($resumeContent));

    // Output the file content
    echo $resumeContent;
    exit;
} else {
    // If no resume found for the given student and job, redirect back
    header('Location: apply_job.php');
    exit;
}
?>
