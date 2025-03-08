<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    // If not logged in, redirect to the login page
    header('Location: homepage.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the firstname from the session
$firstname = $_SESSION['firstname'];

// Establish a connection to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors in connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Prepare SQL statement to fetch student ID using firstname
$sql = "SELECT id FROM student_details WHERE firstname = ?";
$statement = $database->prepare($sql);

// Check for errors in preparing the statement
if (!$statement) {
    die("Error preparing statement: " . $database->error);
}

// Bind parameters
$statement->bind_param("s", $firstname);

// Execute the statement
$result = $statement->execute();

// Check for errors in execution
if (!$result) {
    die("Error executing statement: " . $statement->error);
}

// Bind the result variable
$statement->bind_result($student_id);

// Fetch the result
$statement->fetch();

// Check if student ID is fetched
if (!$student_id) {
    die("Error: Student ID not found for $firstname");
}

// Store student ID in a session variable
$_SESSION['student_id'] = $student_id;

// Close the statement and database connection
$statement->close();
$database->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #73a3d7;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        nav {
            background-color: #73a3d7;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav li {
            display: inline;
            margin: 0 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
        }

        main {
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        p {
            margin-bottom: 0;
        }

     .bottomframe {
            height: 100vh;
        }
    </style>
</head>
<body>
<p><a href="student_login.php"><-Back </a></p>
<header>

    <h1>Student Homepage</h1>
    <h3>Hello, <?php echo $firstname;?>!</h3>
</header>
<div>
    <nav>
        <ul>
            <li><a href="#" onclick="loadPage('student_myprofile.php')">Profile</a></li>
            <li><a href="#" onclick="loadPage('companies_applied.php')">Companies Applied</a></li>
            <li><a href="#" onclick="loadPage('available_jobs.php')">Available Jobs</a></li>
            <li><a href="#" onclick="confirmDelete()">Delete Account</a></li>
            <li><a href="homepage.html">Logout</a></li>
        </ul>
    </nav>
</div>
<div class="bottomframe">
    <iframe id="bottomframe-iframe" name="bottomframe" src="" frameborder="0" style="width: 100%; height: 100%;"></iframe>
</div>
<script>
    function loadPage(page) {
        document.getElementById('bottomframe-iframe').src = page;
    }

    function confirmDelete() {
    if (confirm("Are you sure you want to delete your account?")) {
        window.open('delete_student_account.php', '_blank');
    }
}
</script>
</body>
</html>