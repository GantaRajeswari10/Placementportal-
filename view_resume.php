<?php
// Start the session
session_start();

// Check if the student ID is provided in the session
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    
    // Establish a connection to the database
    $database = new mysqli('localhost', 'root', '', 'placement');

    // Check for errors in connection
    if ($database->connect_error) {
        die("Connection failed: " . $database->connect_error);
    }

    // Prepare SQL statement to fetch the resume content based on student ID
    $sql = "SELECT resume FROM job_applications WHERE student_id = ?";
    $statement = $database->prepare($sql);

    // Check for errors in preparing the statement
    if (!$statement) {
        die("Error preparing statement: " . $database->error);
    }

    // Bind parameters
    $statement->bind_param("i", $student_id);

    // Execute the statement
    $result = $statement->execute();

    // Check for errors in execution
    if (!$result) {
        die("Error executing statement: " . $statement->error);
    }

    // Bind the result variable
    $statement->bind_result($resume_content);

    // Fetch the result
    $statement->fetch();

    // Close the statement
    $statement->close();

    // Close the database connection
    $database->close();

    // Check if resume content is retrieved
    if ($resume_content) {
        // Set headers to display the PDF file in the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="resume.pdf"');
        
        // Output the resume content
        echo $resume_content;
        exit; // Ensure no further output is sent after the PDF content
    } else {
        echo "Resume not found.";
    }
} else {
    // Student ID not provided in the session
    echo "Student ID not provided.";
}
?>
