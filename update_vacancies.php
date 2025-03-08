<?php
session_start();

// Check if the company is logged in
if (!isset($_SESSION['companyname'])) {
    // If not logged in, redirect to the login page
    header('Location: company_login.html');
    exit; // Make sure to exit after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "Cancel" button is clicked
    if (isset($_POST['cancel'])) {
        // Redirect back to view vacancies page
        header('Location: view_vacancies.php');
        exit;
    }
    
    // Check if the vacancy ID is provided
    if (isset($_POST['vacancy_id'])) {
        // Retrieve the vacancy ID
        $vacancy_id = $_POST['vacancy_id'];
        
        // Connect to the database
        $database = new mysqli('localhost', 'root', '', 'placement');

        // Check for errors
        if ($database->connect_error) {
            die("Connection failed: ". $database->connect_error);
        }

        // Retrieve the updated vacancy details from the form
        $title = $_POST['title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];

        // Prepare a statement to update the vacancy details
        $statement = $database->prepare("UPDATE vacancies SET title=?, description=?, location=?, salary=? WHERE id=?");
        $statement->bind_param("ssssi", $title, $description, $location, $salary, $vacancy_id);
        
        // Execute the statement
        if ($statement->execute()) {
            // Vacancy updated successfully, redirect to view vacancies page
            header('Location: view_vacancies.php');
            exit;
        } else {
            // Error occurred while updating vacancy
            echo "Error: " . $statement->error;
        }

        // Close the statement and database connection
        $statement->close();
        $database->close();
    } else {
        // Vacancy ID not provided, redirect to view vacancies page
        header('Location: view_vacancies.php');
        exit;
    }
} else {
    // Invalid request method, redirect to view vacancies page
    header('Location: view_vacancies.php');
    exit;
}
?>
