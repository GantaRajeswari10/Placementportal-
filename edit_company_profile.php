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

// Fetch the company details from the database
$database = new mysqli('localhost', 'root', '', 'placement');
$statement = $database->prepare("SELECT * FROM company_details WHERE companyname =?");
$statement->bind_param("s", $companyname);
$statement->execute();
$result = $statement->get_result();
$company = $result->fetch_assoc();

$name = $company['companyname'];
$email = $company['email'];
$industry = $company['industry'];
$address = $company['address'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        /* company_myprofile.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background-color: 373a3d7;
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
input[type="email"] {
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

input[type="submit"]:hover,
input[type="button"]:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="update_company_profile.php" method="post">
            <label for="companyname">Company Name:</label>
            <input type="text" id="companyname" name="companyname" value="<?php echo $name;?>"><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email;?>"><br>
            <label for="industry">Industry:</label>
            <input type="text" id="industry" name="industry" value="<?php echo $industry;?>"><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $address;?>"><br>
            <input type="submit" value="Update Profile">
            <input type="button" value="Cancel" onclick="location.href='company_myprofile.php';">
        </form>
    </div>
</body>
</html>