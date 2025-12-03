<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Manager</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to styles.css -->
</head>
<body>
    <header>
        <h1>Welcome to Recipe Manager</h1>
    </header>
    <main>
        <section class="welcome">
            <h2>Your Culinary Journey Starts Here!</h2>
            <p>Explore, create, and manage your recipes easily. Start cooking delicious meals today!</p>
        </section>
        <section class="auth">
            <h2>Login or Sign Up</h2>
            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
    </footer>
</body>
</html>
