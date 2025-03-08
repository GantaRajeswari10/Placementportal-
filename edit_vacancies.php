<?php
session_start();

// Check if the company is logged in
if (!isset($_SESSION['companyname'])) {
    // If not logged in, redirect to the login page
    header('Location: company_login.html');
    exit; // Make sure to exit after redirection
}

// Check if the vacancy ID is provided in the URL
if (!isset($_GET['id'])) {
    // If not provided, redirect to the previous page
    header('Location: company_homepage.php');
    exit;
}

// Retrieve the vacancy ID from the URL
$vacancy_id = $_GET['id'];

// Connect to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors
if ($database->connect_error) {
    die("Connection failed: ". $database->connect_error);
}

// Prepare a statement to select the vacancy details based on ID
$statement = $database->prepare("SELECT * FROM vacancies WHERE id = ?");
$statement->bind_param("i", $vacancy_id);
$statement->execute();

// Bind the result to a variable
$result = $statement->get_result();

// Fetch the vacancy details
$vacancy = $result->fetch_assoc();

// Close the statement and database connection
$statement->close();
$database->close();

// Check if the vacancy exists
if (!$vacancy) {
    echo "Vacancy not found!";
    exit;
}

// Display the vacancy details for editing
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vacancy</title>
    <style>
       

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    width: 80%;
    margin: 0 auto;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"],
input[type="button"] {
    background-color: #73a3d7;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
}



    </style>
</head>
<body>
    
    <div class="container">
        <h1>Edit Vacancy</h1>
        <form action="update_vacancies.php" method="post">
            <input type="hidden" name="vacancy_id" value="<?php echo $vacancy_id; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $vacancy['title']; ?>"><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description"><?php echo $vacancy['description']; ?></textarea><br>
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" value="<?php echo $vacancy['location']; ?>"><br>
            <label for="salary">Salary:</label><br>
            <input type="text" id="salary" name="salary" value="<?php echo $vacancy['salary']; ?>"><br>
            <input type="submit" value="Update">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
