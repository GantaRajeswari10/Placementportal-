<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    // If not logged in, redirect to the login page
    header('Location: student_login.html');
    exit; // Make sure to exit after redirection
}

// Retrieve the username from the session
$firstname = $_SESSION['firstname'];

// Fetch the student details from the database
$database = new mysqli('localhost', 'root', '', 'placement');
$statement = $database->prepare("SELECT * FROM student_details WHERE firstname =?");
$statement->bind_param("s", $firstname);
$statement->execute();
$result = $statement->get_result();
$student = $result->fetch_assoc();

$name = $student['firstname']. " ". $student['lastname'];
$email = $student['email'];
$gender = $student['gender'];
$phone = $student['phone'];
$address = $student['address'];



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .edit {
            float: right;
            color: #333;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #333;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .edit:hover {
            background-color: #333;
            color: #fff;
        }

        .profile-info {
            margin-top: 30px;
        }

        .profile-info p {
            margin-bottom: 10px;
        }
    </style>

    <script>
        function openEditProfile() {
            window.location.href = "edit_student_profile.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        <p><a class="edit" href="javascript:void(0)" onclick="openEditProfile()">Edit Profile</a></p>
        <div class="profile-info">
            <p>Name: <?php echo $name;?></p>
            <p>Email: <?php echo $email;?></p>
            <p>Gender: <?php echo $gender;?></p>
            <p>Phone Number: <?php echo $phone;?></p>
            <p>Address: <?php echo $address;?></p>
        </div>
    </div>
</body>
</html>
