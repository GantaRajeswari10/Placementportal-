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

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: ". $database->connect_error);
}

// Prepare SQL statement with a parameter
$sql = "SELECT j.application_date, s.firstname, s.lastname, v.title, j.student_id,j.job_id
        FROM job_applications j
        INNER JOIN student_details s ON j.student_id = s.id
        INNER JOIN company_details c ON j.company_id = c.id
        INNER JOIN vacancies v ON j.job_id = v.id
        WHERE c.companyname =?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: ". $database->error);
}

// Bind parameter
$statement->bind_param("s", $companyname);

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: ". $statement->error);
}

// Get the result set
$resultSet = $statement->get_result();

// Close the statement
$statement->close();

// Close the database connection
$database->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <style>
        /* CSS styles here */
    </style>
</head>
<body>
    <h2>View Applicants</h2>
    <table border='2px'>
        <thead>
            <tr>
                <th>Applied Date</th>
                <th>Student Name</th>
                <th>Job Title</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultSet->fetch_assoc()):?>
                <tr>
                    <td><?php echo $row['application_date'];?></td>
                    <td><?php echo $row['firstname']. ' '. $row['lastname'];?></td>
                    <td><?php echo $row['title'];?></td>
                    
                    <td><a href="job_applied_student_application.php?student_id=<?php echo $row['student_id'];?>&job_id=<?php echo $row['job_id'];?>">View application</a></td>
                </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</body>
</html>