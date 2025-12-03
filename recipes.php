<?php
session_start();
include 'config/db.php';

// Fetch recipes in descending order by recipe_id
$sql = "SELECT recipe_id, title, description, recipe_image FROM recipe ORDER BY recipe_id DESC";
$result = $conn->query($sql);

// Check if there are any recipes
if ($result->num_rows > 0) {
    // Store the recipes in an array
    $recipes = [];
    while($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
} else {
    $recipes = [];
}

$conn->close(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your stylesheet -->
    <style>
        body {
            color: black;
        }

        .view-recipe {
            display: inline-block;
            padding: 10px 15px;
            background-color: #647a70;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .view-recipe:hover {
            background-color: #00b35c;
        }

        nav ul li a {
            color: black;
            text-decoration: none;
        }

        .search-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #647a70;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 10px;
            text-align: center;
        }

        .search-button:hover {
            background-color: #00b35c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="recipes.php">Recipes</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="shopping_list.php">Shopping List</a></li>
                <li><a href="upload_recipe.php">Upload Your Recipes</a></li>
                <li><a href="saved_recipes.php">Saved Recipes</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <a href="search.php" class="search-button">Search</a>
    </header>
    <main>
        <section class="welcome">
            <h2>Your culinary journey starts here!</h2>
            <p>Explore, create, and manage your recipes easily.</p>
        </section>
        <section class="all-recipes">
            <h2>All Recipes</h2>
            <ul>
                <?php if (!empty($recipes)): ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($recipe['title'] ?? 'Untitled'); ?></h3>
                            <p><?php echo htmlspecialchars($recipe['description'] ?? 'No description available.'); ?></p>
                            <a class="view-recipe" href="recipe_detail.php?id=<?php echo htmlspecialchars($recipe['recipe_id']); ?>">View Recipe</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No recipes found.</li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
    </footer>
</body>
</html>
