<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
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
    die("Connection failed: " . $database->connect_error);
}

// Prepare SQL statement to fetch job applications with company names and job titles for the student
$sql = "SELECT c.companyname, v.title, j.job_id
        FROM job_applications j
        INNER JOIN company_details c ON j.company_id = c.id
        INNER JOIN vacancies v ON j.job_id = v.id
        WHERE j.student_id = ?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: " . $database->error);
}

// Bind parameters
$statement->bind_param("i", $student_id); // Assuming student_id is an integer

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: " . $statement->error);
}

// Get the result set
$resultSet = $statement->get_result();

// Close the statement (we'll use the same database connection)
$statement->close();

// Close the database connection
$database->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies Applied</title>
    <style>
        /* Styles for Companies Applied page */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
        }

        th {
            background-color: #73a3d7;
        }

        a {
            text-decoration: none;
            color: #333;
        }

        a:hover {
            color: #555;
        }

    </style>
</head>
<body>
    
    <h2>Companies Applied</h2>
    <table border='2px'>
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Job Title</th>
                <th>Company Name</th>
                <th>Details</th>
                <!-- Add more columns if needed -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $serial_number = 1; 
            while ($row = $resultSet->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $serial_number++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['companyname']; ?></td>
                    <td><a href="job_details.php?job_id=<?php echo $row['job_id']; ?>">View Details</a></td>
                    <!-- Display more columns if needed -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
