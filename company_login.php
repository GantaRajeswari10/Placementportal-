<?php
// Start the session
session_start();

// Database connection
$database = new mysqli('localhost', 'root', '', 'placement');

if ($database->connect_error) {
    die('Connection failed: '. $database->connect_error);
} else {
    // Check if username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare a select statement
        $statement = $database->prepare("SELECT * FROM company_details WHERE companyname =? AND password =?");
        $statement->bind_param("ss", $username, $password);

        // Execute the statement and check if the company exists
        if ($statement->execute()) {
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                // Company exists, set session variable and redirect to home page
                $_SESSION['companyname'] = $username;
                header('Location:company_homepage.php');
                exit(); // Make sure to exit after redirection
            } else {
                // Company does not exist, display an error message
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error: ". $statement->error;
        }

        $statement->close();
    }

    $database->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
    font-family: Georgia, 'Times New Roman', Times, serif;
    margin: 0;
    padding: 0;
    background-color: #73a3d7;
}

header {
    background-color: #73a3d7;
    color: #fff;
    padding: 10px;
    text-align: center;
    padding-bottom: 0%;
    
}

header h1 {
    margin: 0;
}

main {
    display: flex; /* Display the image and form side by side */
    align-items: center;
    justify-content: center;
    height: 100vh;
    padding: 20px;
    padding-top: 0%; /* Add some padding for spacing */
}

.image-container, form {
    flex: 1; /* Each takes half of the available space */
    margin: 10px 10px; /* Add some space between the elements */
}

.image-container {
    background-image: url("placement_img.webp");
    background-repeat: no-repeat;
    background-size: cover;
    height: 60%;
    border-radius: 25px; /* Optional: to match the form's border-radius */
    
}

form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border: 1px solid #ccc;
    padding: 40px;
    background-color: #fff;
    box-shadow: #040404 5px 5px 5px;
    border-radius: 15px;
    height: 50%;
    width: 15%;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input[type="text"],
form input[type="password"] {
    width: 70%;
    padding-left: 5px;
    margin-bottom: 10px;
}

form button {
    background-color: #73a3d7;
    color: #fff;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 10px;
}
.label-input{
    display: flex;
    flex-direction: row;
    padding: 5px;;
}

.signup-link {
    text-align: center;
    margin-top: 10px;
}

.signup-link a {
    color: #333;
    text-decoration: none;
}

.sign_up_btn {
    border-radius: 10px;
    background-color: #73a3d7;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.back-link {
    margin-top: 10px;
    display: flex;
    justify-content: left;
}

.back-link a {
    color: #faf7f7;
    text-decoration: none;
    font-size: larger;
    padding-left: 10px;
}

.error-message {
    color: red;
    text-align: center;
}
    </style>
</head>
<body>
    <header> 
        <div class="top">
            <div class="back-link">
                <a href="homepage.html"> < </a>
            </div>
        <h1 class="heading1">Company Login</h1> 
        </div>

        

    </header> 
    
<main>
    <div class="image-container"></div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <br>
    
        <div class="label-input">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="label-input">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <center><button style="color: #040404;" type="submit">Log In</button></center>
        <div class="signup-link" >
            <p style="text-align: center;color black;">Don't have an account? <button class="sign_up_btn"><a href="company_signup.php">Sign up here</a></button>
            </p>
        </div>
    </form>
    
</main>
</body>
</html>
