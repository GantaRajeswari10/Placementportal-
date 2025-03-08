<?php
session_start();

// Check if the vacancy ID exists in the session variable
if (!isset($_SESSION['vacancy_id'])) {
    // If not, redirect to the view vacancies page
    header('Location: view_vacancies.php');
    exit;
}

// Connect to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Retrieve the vacancy ID from the session
$vacancy_id = $_SESSION['vacancy_id'];

// Prepare a statement to delete the vacancy by ID
$statement = $database->prepare("DELETE FROM vacancies WHERE id = ?");
$statement->bind_param("i", $vacancy_id);

// Execute the statement
if ($statement->execute()) {
    // Close the statement and database connection
    $statement->close();
    $database->close();

    // Clear the vacancy ID session variable
    unset($_SESSION['vacancy_id']);

    // Provide confirmation alert
    echo "<script>alert('Vacancy deleted successfully');</script>";

    // Redirect to the view vacancies page
    header('Location: view_vacancies.php');
    exit;
} else {
    // Error occurred
    echo "Error: " . $statement->error;
}

// Close the statement and database connection
$statement->close();
$database->close();
?>
