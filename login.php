<?php
session_start();
include 'config/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    
    $stmt = $conn->prepare("SELECT * FROM User WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // password verification
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['name'];
            header("Location: recipes.php"); // Redirect to home 
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
    $conn->close();
}
?>
