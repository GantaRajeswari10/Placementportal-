<?php
// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Check if the job ID is provided in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Prepare SQL statement to fetch job details
    $sql = "SELECT * FROM vacancies WHERE id = ?";
    $statement = $database->prepare($sql);

    // Check for errors in preparing the statement
    if (!$statement) {
        die("Error preparing statement: " . $database->error);
    }

    // Bind parameters
    $statement->bind_param("i", $job_id);

    // Execute the statement
    $result = $statement->execute();

    // Check for errors in execution
    if (!$result) {
        die("Error executing statement: " . $statement->error);
    }

    // Get the result set
    $resultSet = $statement->get_result();

    // Check if job exists
    if ($resultSet->num_rows > 0) {
        $job = $resultSet->fetch_assoc();
    } else {
        echo "Job not found.";
    }

    // Close the statement
    $statement->close();
} else {
    echo "Job ID not provided.";
}

// Close the database connection
$database->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <style>
        /* CSS styles for Job Details page */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        h3 {
            color: #333;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #333;
        }

        a:hover {
            color: #555;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            background-color: #73a3d7;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-link a:hover {
            background-color: #5585ba;
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 1.5rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Job Details</h2>
        <?php if (isset($job)): ?>
            <h3>Title: <?php echo $job['title']; ?></h3>
            <p>Salary: <?php echo $job['salary']; ?></p>
            <p>Location: <?php echo $job['location']; ?></p>
            <!-- Add more details if needed -->
        <?php else: ?>
            <p>Job not found.</p>
        <?php endif; ?>
        <p class="back-link"><a href="javascript:history.back()">Back to Applied Jobs</a></p>
    </div>
</body>
</html>
