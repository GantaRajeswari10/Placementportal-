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

// Database connection
$database = new mysqli('localhost', 'root', '', 'placement');

// Check database connection
if ($database->connect_error) {
    die('Connection failed: ' . $database->connect_error);
}

// Create the vacancies table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS `vacancies` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `companyname` varchar(20) NOT NULL,
                      `description` text NOT NULL,
                      `location` varchar(100) NOT NULL,
                      `salary` varchar(20) NOT NULL,
                      `title` varchar(20) NOT NULL,
                      PRIMARY KEY (`id`)
                    )";
if ($database->query($create_table_sql) === FALSE) {
    echo "Error creating table: " . $database->error;
    exit();
}

// Insert the vacancy into the database
$statement = $database->prepare("INSERT INTO vacancies (title, description, location, salary, companyname) VALUES (?,?,?,?,?)");
$statement->bind_param("sssis", $_POST['title'], $_POST['description'], $_POST['location'], $_POST['salary'], $companyname);

if ($statement->execute()) {
    // Redirect the user to their vacancies page
    header('Location: view_vacancies.php');
    exit();
} else {
    echo "Error: " . $statement->error;
}

$statement->close();
$database->close();
?>
