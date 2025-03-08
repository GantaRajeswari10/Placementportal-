<?php
session_start();

// Check if student_id, company_id, and job_id are set in the URL
if (!isset($_GET['student_id']) || !isset($_GET['company_id']) || !isset($_GET['id'])) {
    // Redirect to homepage or display an error message
    header('Location: homepage.html');
    exit; // Make sure to exit after redirection
}

// Store student_id, company_id, and job_id in session variables
$_SESSION['student_id'] = $_GET['student_id'];
$_SESSION['company_id'] = $_GET['company_id'];
$_SESSION['job_id'] = $_GET['id'];

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Prepare SQL statement to check if the student has already applied for this job
$check_sql = "SELECT * FROM job_applications WHERE student_id = ? AND job_id = ?";
$check_statement = $database->prepare($check_sql);
$check_statement->bind_param("ii", $_SESSION['student_id'], $_SESSION['job_id']);
$check_statement->execute();
$check_result = $check_statement->get_result();

// Check if the student has already applied
if ($check_result->num_rows > 0) {
    $error_message = "You have already applied for this job.";
}

// Close the check statement
$check_statement->close();

// Close the database connection
$database->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <style>
        /* styles.css */

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #73a3d7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: white;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

    </style>
</head>
<body>
    <h1>Apply for Job</h1>
    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php else: ?>
        <form action="submit_application.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">
            <input type="hidden" name="company_id" value="<?php echo $_SESSION['company_id']; ?>">
            <input type="hidden" name="job_id" value="<?php echo $_SESSION['job_id']; ?>">

            <label for="resume">Resume:</label><br>
            <input type="file" id="resume" name="resume" required><br><br>

            <label for="cover_letter">Cover Letter:</label><br>
            <textarea id="cover_letter" name="cover_letter" rows="5" cols="40" required></textarea><br><br>

            <label for="additional_info">Additional Information:</label><br>
            <textarea id="additional_info" name="additional_info" rows="5" cols="40"></textarea><br><br>

            <input type="submit" value="Submit Application">
        </form>
    <?php endif; ?>
</body>
</html>