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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vacancies</title>
    <style>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

.container {
    width: 80%;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    text-align: center;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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
    
    <div class="container">
        <h1>View Vacancies</h1>
        <h2>My Company's Vacancies</h2>
        <table border='2px'>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Salary</th>
                <th>View Applicants</th> <!-- New column -->
                <th>Action</th>
            </tr>
            <?php
            // Connect to the database
            $database = new mysqli('localhost', 'root', '', 'placement');

            // Check for errors
            if ($database->connect_error) {
                die("Connection failed: ". $database->connect_error);
            }

            // Retrieve the company name from the session
            $companyname = $_SESSION['companyname'];

            // Prepare a statement to select all vacancies for the company
            $statement = $database->prepare("SELECT * FROM vacancies WHERE companyname =?");
            $statement->bind_param("s", $companyname);
            $statement->execute();

            // Bind the result to a variable
            $result = $statement->get_result();

            // Fetch and display the vacancies
            while ($vacancy = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>". $vacancy['title']. "</td>";
                echo "<td>". $vacancy['description']. "</td>";
                echo "<td>". $vacancy['location']. "</td>";
                echo "<td>". $vacancy['salary']. "</td>";
                echo "<td><a href='view_applicants.php?vacancy_id=". $vacancy['id']. "'>View Applicants</a></td>"; 
                echo "<td><a href='edit_vacancies.php?id=". $vacancy['id']. "'>Edit</a> | <a href='delete_vacancy.php?id=". $vacancy['id']. "'>Delete</a></td>";
                $_SESSION['vacancy_id'] = $vacancy['id'];
                echo "</tr>";
            }

            // Close the statement and database connection
            $statement->close();
            $database->close();
       ?>
        </table>
        <h2>Other Companies' Vacancies</h2>
        <table border='2px'>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Salary</th>
            </tr>
            <?php
            // Connect to the database
            $database = new mysqli('localhost', 'root', '', 'placement');

            // Check for errors
            if ($database->connect_error) {
                die("Connection failed: ". $database->connect_error);
            }

            // Prepare a statement to select all vacancies for other companies
            $statement = $database->prepare("SELECT * FROM vacancies WHERE companyname!=?");
            $statement->bind_param("s", $companyname);
            $statement->execute();

            // Bind the result to a variable
            $result = $statement->get_result();

            // Fetch and display the vacancies
            while ($vacancy = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>". $vacancy['title']. "</td>";
                echo "<td>". $vacancy['description']. "</td>";
                echo "<td>". $vacancy['location']. "</td>";
                echo "<td>". $vacancy['salary']. "</td>";
                echo "</tr>";
            }

            // Close the statement and database connection
            $statement->close();
            $database->close();
       ?>
        </table>
    </div>
</body>
</html>
