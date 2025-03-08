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

$firstname = $student['firstname'];
$lastname= $student['lastname'];
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
    <title>Edit Profile</title>
    
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

        form {
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"],
        input[type="button"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #45a049;
        }

        input[type="button"] {
            background-color: #f44336;
        }

        input[type="button"]:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="update_student_profile.php" method="post">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $firstname;?>" required><br><br>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $lastname;?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email;?>" required><br><br>

            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" value="<?php echo $gender;?>" required><br><br>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $phone;?>" pattern="[0-9]{10}" required><br><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $address;?>" required><br><br>

            <input type="submit" value="Update Profile">
            <input type="button" value="Cancel" onclick="location.href='student_myprofile.php';">
        </form>
    </div>
</body>
</html>
