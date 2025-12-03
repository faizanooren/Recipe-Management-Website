<?php
session_start();
include 'config/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for storage

    $stmt = $conn->prepare("SELECT * FROM User WHERE name = ?");
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already taken. Please choose another.";
    } else {
        $stmt = $conn->prepare("INSERT INTO User (name, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
   
        
        if ($stmt->execute()) {
            
            $_SESSION['user_id'] = $stmt->insert_id; 
            $_SESSION['username'] = $username; 
            header("Location: recipes.php"); 
            exit();
        } else {
            echo "Error during registration. Please try again.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to styles.css -->
</head>
<body>
    <header>
        <h1>Sign Up for Recipe Manager</h1>
    </header>
    <main>
        <form action="signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Sign Up</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
    </footer>
</body>
</html>
