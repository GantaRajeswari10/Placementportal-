
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
    <title>Company Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: ;
        }

        header {
            background-color:#73a3d7;
            color: #fff;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
            text-align: center;
            display:flex;
            flex-direction: row;
            
        }
       
        header p{
           
            color:white;
            font-size:larger;
            text-decoration: none;
            width:50%;
            
        }
        header a{
            float:left;
            text-decoration:none;
            color:white;
            font-size:larger;
        }
        .back-icon{
            
        }
        

        nav {
            background-color: #73a3d7;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav li {
            display: inline;
            margin: 0 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
        }

        main {
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        p {
            margin-bottom: 0;
        }
        .bottomframe {
            height: 100vh;
        }
    </style>
</head>
<body>
    

<header>
    <div class=back-icon>
    <p><a href="company_login.php">< </a></p>
    <img src= "icon_prev_ui.png" alt="logo" width="100px" height="100px">
    </div>
    <div class=head>
    <h1>Company Homepage</h1>
    
    <h3>Hello, <?php echo $companyname;?>!</h3>
    </div>
</header>
<div>
    <nav>
        <ul>
            <li><a href="#" onclick="loadPage('company_myprofile.php')">Profile</a></li>
            
            <li><a href="#" onclick="loadPage('add_vacancies.php')">Add Vacancies</a></li>
            <li><a href="#" onclick="loadPage('view_vacancies.php')">View vacancies</a></li>
            <li><a href="#" onclick="confirmDelete()">Delete Account</a></li>
            <li><a href="homepage.html">Logout</a></li>
           
        </ul>
    </nav>
    </div>
<div class="bottomframe">
    <iframe id="bottomframe-iframe" name="bottomframe" src="" frameborder="0" style="width: 100%; height: 100%;"></iframe>
</div>
   
<script>
    function loadPage(page) {
        document.getElementById('bottomframe-iframe').src = page;
    }

    function confirmDelete() {
    if (confirm("Are you sure you want to delete your account?")) {
        window.open('delete_company_account.php', '_blank');
    }
}
</script>
</body>
</html>