
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vacancies</title>
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
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 20%;
    padding: 10px;
    background-color: #73a3d7;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}



</style>
</head>
<body>
   
    <div class="container">
        <h1>Add Vacancies</h1>
        <form action="insert_vacancies.php" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required><br>
            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" min="1" required><br>
            <center><input type="submit" value="Add Vacancy"><center>
        </form>
    </div>
</body>
</html>