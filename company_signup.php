<?php
// Start the session
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "placement";

// Create connection
$database = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($database->connect_error) {
    die('Connection failed: '. $database->connect_error);
} else {
    // Create the company_details table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS `company_details` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `companyname` varchar(50) NOT NULL,
                          `email` varchar(40) NOT NULL,
                          `password` varchar(10) NOT NULL,
                          `industry` varchar(20) NOT NULL,
                          `address` varchar(100) NOT NULL,
                          PRIMARY KEY (`id`)
                        )";
    if ($database->query($create_table_sql) === FALSE) {
        echo "Error creating table: ". $database->error;
        exit();
    }

    // Check if companyname and password are provided
    if (isset($_POST['companyname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmpassword']) && isset($_POST['industry']) && isset($_POST['address'])) {
        $companyname = $_POST['companyname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirmpassword'];
        $industry = $_POST['industry'];
        $address = $_POST['address'];

        // Check if password and confirm password match
        if ($password !== $confirm_password) {
            $error_message = "Password and confirm password do not match.";
        } else {
            // Check for duplicate email
            $check_duplicate_sql = "SELECT * FROM company_details WHERE email = '$email'";
            $result = $database->query($check_duplicate_sql);
            if ($result && $result->num_rows > 0) {
                $error_message = "Email already exists. Please use a different email.";
            } else {
                // Prepare an insert statement
                $statement = $database->prepare("INSERT INTO company_details (companyname, email, password, industry, address) 
                    VALUES (?,?,?,?,?)");
                $statement->bind_param("sssss", $companyname, $email, $password, $industry, $address);

                // Execute the statement
                if ($statement->execute()) {
                    // Registration successful, set session variable and redirect to company homepage
                    $_SESSION['companyname'] = $companyname;
                    header('Location: company_homepage.php');
                    exit(); // Make sure to exit after redirection
                } else {
                    echo "Error: ". $statement->error;
                }

                $statement->close();
            }
        }
    }

    $database->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Sign Up</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        header{
            background-color: #73a3d7; 
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        form label{
            display: block;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="password"]{
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        form button{
            background-color: #73a3d7;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .back-link {
            text-align: center;
            margin-top: 10px;
            display: flex;
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
        <h1>Company Sign Up</h1>
        <div class="back-link">
            <a href="homepage.html"><-Back</a>
        </div>
    </header>
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <main>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> 
            <label for="companyname">Company Name:</label>
            <input type="text" id="companyname" name="companyname" required> 
            <label for="email">Email:</label> 
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required> 
            <label for="confirmpassword">Confirm Password:</label> 
            <input type="password" id="confirmpassword" name="confirmpassword" required> 
            <label for="industry">Industry:</label> 
            <input type="text" id="industry" name="industry" required> 
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" cols="30" required></textarea>
            <center><button type="submit">Sign Up</button></center>
        </form>
    </main>
</body>
</html>
