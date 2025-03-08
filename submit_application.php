<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // If not logged in, redirect to the login page
    header('Location: apply_job.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $database = new mysqli('localhost', 'root', '', 'placement');

    // Check for errors in connection
    if ($database->connect_error) {
        die("Connection failed: " . $database->connect_error);
    }
    
    // Create the job_applications table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS `job_applications` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `job_id` int(11) DEFAULT NULL,
                          `student_id` int(11) DEFAULT NULL,
                          `company_id` int(4) NOT NULL,
                          `resume` longblob DEFAULT NULL,
                          `resume_name` varchar(255) DEFAULT NULL,
                          `cover_letter` text DEFAULT NULL,
                          `additional_info` text DEFAULT NULL,
                          `application_date` date DEFAULT CURRENT_DATE,
                          PRIMARY KEY (`id`),
                          FOREIGN KEY (`job_id`) REFERENCES `vacancies` (`id`),
                          FOREIGN KEY (`student_id`) REFERENCES `student_details` (`id`),
                          FOREIGN KEY (`company_id`) REFERENCES `company_details` (`id`)
                        )";
    if ($database->query($create_table_sql) === FALSE) {
        die("Error creating table: " . $database->error);
    }

    // Prepare statement to insert application data into the database
    $statement = $database->prepare("INSERT INTO job_applications (job_id, student_id, company_id, resume, resume_name, cover_letter, additional_info) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$statement) {
        die("Error preparing statement: " . $database->error);
    }
    $statement->bind_param("iiissss", $jobId, $studentId, $companyId, $resumeContent, $resumeName, $coverLetter, $additionalInfo);

    // Get form data
    $jobId = $_POST['job_id'];
    $studentId = $_SESSION['student_id'];
    $companyId = $_SESSION['company_id'];
    $coverLetter = $_POST['cover_letter'];
    $additionalInfo = $_POST['additional_info'];

    // Get file content and name
    $resumeName = $_FILES['resume']['name'];
    $resumeTmpName = $_FILES['resume']['tmp_name'];
    $resumeContent = file_get_contents($resumeTmpName); // Read file content

    // Move uploaded file to desired location (uploads directory)
    $uploadDirectory = 'uploads/';
    $resumePath = $uploadDirectory . $resumeName;
    if (!move_uploaded_file($resumeTmpName, $resumePath)) {
        die("Error moving file to destination");
    }

    // Execute the statement
    if (!$statement->execute()) {
        die("Error executing statement: " . $statement->error);
    }

    // Close the statement and database connection
    $statement->close();
    $database->close();

    // Redirect the user to a confirmation page
    header('Location: application_confirmation.php');
    exit;
} else {
    // If the request method is not POST, redirect back to the form page
    header('Location: apply_job.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <style>
        /* CSS styles for Job Application page */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        textarea {
            height: 100px;
            resize: vertical;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #73a3d7;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #5585ba;
        }

        .error {
            color: red;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Apply for Job</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $_GET['job_id']; ?>">
            <label for="resume">Resume:</label>
            <input type="file" name="resume" required>
            <label for="cover_letter">Cover Letter:</label>
            <textarea name="cover_letter" required></textarea>
            <label for="additional_info">Additional Information:</label>
            <textarea name="additional_info"></textarea>
            <input type="submit" value="Apply">
        </form>
    </div>
</body>
</html>
