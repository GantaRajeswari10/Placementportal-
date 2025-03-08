<?php
session_start();

// Connect to the database
$database = new mysqli('localhost', 'root', '', 'placement');

// Check for errors
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Prepare a statement to select all vacancies
$statement = $database->prepare("SELECT v.id, v.title, v.description, v.location, v.salary, c.id AS company_id, c.companyname FROM vacancies v INNER JOIN company_details c ON v.companyname = c.companyname");

// Check for errors in preparation
if (!$statement) {
    die("Error preparing statement: " . $database->error);
}

// Execute the statement
$statement->execute();

// Bind the result to a variable
$result = $statement->get_result();

if (!isset($_SESSION['company_id'])) {
    // Fetch the company ID from the first row if it's not already set
    if ($row = $result->fetch_assoc()) {
        $_SESSION['company_id'] = $row['company_id'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vacancies</title>
    <link rel="stylesheet" href="company_myprofile.css">
</head>
<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
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
    text-align: left;
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
        <h2>All Available Jobs</h2>
        <table border='2px'>
            <tr>
                <th>Company Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Action</th>
            </tr>
            <?php while ($vacancy = $result->fetch_assoc()): ?>
                <?php
                $companyName = $vacancy['companyname'];
                $title = $vacancy['title'];
                $description = $vacancy['description'];
                $location = $vacancy['location'];
                $salary = $vacancy['salary'];
                $vacancyId = $vacancy['id'];
                $companyId = $vacancy['company_id'];
                ?>
                <tr>
                    <td><?php echo $companyName; ?></td>
                    <td><?php echo $title; ?></td>
                    <td><?php echo $description; ?></td>
                    <td><?php echo $location; ?></td>
                    <td><?php echo $salary; ?></td>
                    <td><a href='apply_job.php?id=<?php echo $vacancyId; ?>&student_id=<?php echo $_SESSION['student_id']; ?>&company_id=<?php echo $companyId; ?>'>Apply</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>

<?php
// Close the statement and database connection
$statement->close();
$database->close();
?>
