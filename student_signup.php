<?php
// Start session to use session variables
session_start();

// Database connection 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "placement";

// Create connection
$database = new mysqli($servername, $username, $password);

// Check connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Create the database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($database->query($sql_create_db) === FALSE) {
    echo "Error creating database: " . $database->error;
}

// Select the database
$database->select_db($dbname);

// Check if table exists
$table_name = "student_details";
$table_check_query = "SHOW TABLES LIKE '$table_name'";
$table_check = $database->query($table_check_query);

if ($table_check->num_rows == 0) {
    // Table doesn't exist, create it
    $sql = "
    CREATE TABLE `student_details` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `firstname` varchar(20) NOT NULL,
      `lastname` varchar(20) NOT NULL,
      `email` varchar(50) NOT NULL,
      `password` varchar(10) NOT NULL,
      `gender` varchar(10) NOT NULL,
      `phone` bigint(10) NOT NULL,
      `address` text NOT NULL,
      PRIMARY KEY (`id`)
    )";

    // Execute SQL command to create table
    if ($database->query($sql) === FALSE) {
        echo "Error creating table: " . $database->error;
    }
}

// Initialize error message variable
$error_message = '';

// Insert student details into database
if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Check if passwords match
    if ($password !== $confirmpassword) {
        $error_message = "Error: Passwords do not match.";
    } else {
        // Check if the email already exists
        $check_duplicate_query = "SELECT * FROM student_details WHERE email=?";
        $check_duplicate_statement = $database->prepare($check_duplicate_query);
        $check_duplicate_statement->bind_param("s", $email);
        $check_duplicate_statement->execute();
        $check_duplicate_result = $check_duplicate_statement->get_result();

        if ($check_duplicate_result->num_rows > 0) {
            $error_message = "Error: User already exists";
        } else {
            // Insert student details if email is not a duplicate
            $statement = $database->prepare("INSERT INTO student_details (firstname, lastname, gender, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sssssss", $firstname, $lastname, $gender, $email, $password, $phone, $address);

            if ($statement->execute()) {
                // Store the firstname in a session variable
                $_SESSION['firstname'] = $firstname;
                $_SESSION['password'] = $password;
                $database->close();
                header("Location: student_homepage.php");
                exit();
            } else {
                $error_message = "Error: " . $statement->error;
            }

            $statement->close();
        }
        
        // Close the check_duplicate_statement if it was initialized
        if (isset($check_duplicate_statement)) {
            $check_duplicate_statement->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f0f0f0;
        }
        header{
            background-color:#73a3d7; 
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        header h1{
            margin: 0;
        }
        main{
            padding: 20px;
        }
        form{
            width: 300px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(31, 25, 25, 0.1);
        }
        form label{
            display: block;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="email"],
        form textarea,
        form input[type="tel"]{
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        form button{
            background-color:#73a3d7; 
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 10px;
        }
        .back-link {
            margin-top: 10px;
            display: flex;
            justify-content: left;
        }
        .back-link a{
            color: #333;
            text-decoration: none;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head> 
<body> 
    <header> 
        <h1>Sign Up</h1> 
        <div class="back-link">
            <a href="student_login.php"><- Back</a>
        </div>  
        <?php
        // Display error message if exists
        if (!empty($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
    </header> 
    
    <main> 
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> 
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required> 
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required> 
            <label for="email">Email:</label> 
            <input type="email" id="email" name="email" required> 
            <label for="password">Password:</label> 
            <input type="password" id="password" name="password" required>
            <label for="confirmpassword">Confirm Password:</label> 
            <input type="password" id="confirmpassword" name="confirmpassword" required>
            <label for="gender">Gender:</label> 
            <label><input type="radio" name="gender" id="male" value="male" required> Male</label>
            <label><input type="radio" name="gender" id="female" value="female" required> Female</label> 
            <label><input type="radio" name="gender" id="others" value="others" required> Others</label> 
            <label for="phone">Phone Number:</label> 
            <input type="tel" id="phone" name="phone" required>
            <label for="address">Address:</label> 
            <textarea id="address" name="address" rows="4" cols="30" required></textarea>
            <center><button type="submit" name="submit">Sign Up</button></center>
        </form>
    </main>
</body> 
</html>
