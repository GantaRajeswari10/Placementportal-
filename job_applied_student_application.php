<?php
// Start the session
session_start();

// Retrieve the student ID from the URL parameter
if(isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $_SESSION['student_id'] = $_GET['student_id']; // Assign student_id to session variable
    $student_id = $_GET['student_id'];
} else {
    echo "Invalid student ID";
    exit; // Exit the script if student ID is not provided or invalid
}

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: ". $database->connect_error);
}

// Prepare SQL statement to fetch student details including additional info from job applications
$sql = "SELECT s.firstname, s.lastname, s.gender, s.phone, s.address, j.resume_name, j.cover_letter, j.job_id
        FROM student_details s
        INNER JOIN job_applications j ON s.id = j.student_id
        WHERE s.id = ?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: ". $database->error);
}

// Bind parameters
$statement->bind_param("i", $student_id); // Assuming student_id is an integer

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: ". $statement->error);
}

// Get the result set
$resultSet = $statement->get_result();

// Fetch the student details including additional info from job applications
$student_details = $resultSet->fetch_assoc();

// Check if $student_details is not null
if ($student_details!== null) {
    // Now you can use $student_details array to access student information and additional info
    // For example:
    $firstname = $student_details['firstname'];
    $lastname = $student_details['lastname'];
    $gender = $student_details['gender'];
    $phone = $student_details['phone'];
    $address = $student_details['address'];
    $resume = $student_details['resume_name'];
    $cover_letter = $student_details['cover_letter'];
    $job_id = $_GET['job_id'];
    // Add more fields as needed
} else {
    echo "Student details not found.";
}

// Close the statement and database connection
$statement->close();
$database->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <!-- Add your CSS styles here -->
</head>
<body>
    <h1>Student Details</h1>
    <p><strong>First Name:</strong> <?php echo $firstname;?></p>
    <p><strong>Last Name:</strong> <?php echo $lastname;?></p>
    <p><strong>Gender:</strong> <?php echo $gender;?></p>
    <p><strong>Phone:</strong> <?php echo $phone;?></p>
    <p><strong>Address:</strong> <?php echo $address;?></p>
    <p><strong>Resume:</strong><?php echo $job_id ;?></p>
    <p><a href="download_resume.php?student_id=<?php echo $_SESSION['student_id']; ?>&job_id=<?php echo $job_id; ?>">Download Resume</a></p>

    <p><strong>Cover Letter:</strong></p>
    <p><?php echo $cover_letter;?></p>
    <!-- Display more student details as needed -->
</body>
</html>
