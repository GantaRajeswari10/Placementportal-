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
$statement = $database->prepare("SELECT * FROM company_details WHERE companyname = ?");
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
    <title>My Profile</title>
    <link rel="stylesheet" href="company_myprofile.css">
    <style>
        .edit{
            float:right;
            color:black;
            text-decoration:none;
            background-color:white;
            height:20px;
            width:80px;
            border-radius:10px;
            padding:5px;
        }
        /* company_myprofile.css */

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

h1 {
    text-align: center;
    color: #333;
}

.profile-info {
    margin-top: 20px;
}

.profile-info p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 10px;
}

.edit {
    float: right;
    color:#73a3d7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 5px 10px;
    transition: background-color 0.3s, color 0.3s;
}

.edit:hover {
    background-color: #333;
    color: #fff;
}



        </style>
</head>
<body>
<div class="container">
    <h1>My Profile</h1>
    <p ><a  class="edit" href="edit_company_profile.php">Edit Profile</a></p>
    <div class="profile-info">
        <p>Company Name: <?php echo $name;?></p>
        <p>Email: <?php echo $email;?></p>
        <p>Industry: <?php echo $industry;?></p>
        <p>Address: <?php echo $address;?></p>
    </div>
</div>
</body>
</html>
