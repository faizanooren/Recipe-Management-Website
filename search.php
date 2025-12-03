<?php
include('config/db.php'); 


// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search form submission
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = trim($_POST['search_query']);
    if (!empty($search_query)) {
        $search_query_like = "%" . $search_query . "%"; // Prepare for SQL LIKE

        // Query to search in Recipe title, Ingredients, and Category
        $query = "
            SELECT r.recipe_id, r.title, r.instructions, r.prep_time, r.cook_time, r.servings, r.category, 
                   r.rating, r.nutrition_info, GROUP_CONCAT(i.name SEPARATOR ', ') AS ingredients
            FROM Recipe r
            LEFT JOIN Menu m ON r.recipe_id = m.recipe_id
            LEFT JOIN Ingredients i ON m.ingredient_id = i.ingredient_id
            WHERE r.title LIKE ? OR i.name LIKE ? OR r.category LIKE ?
            GROUP BY r.recipe_id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $search_query_like, $search_query_like, $search_query_like); // Bind parameters
        $stmt->execute(); // Execute the prepared statement
        $result = $stmt->get_result();
        $search_results = $result->fetch_all(MYSQLI_ASSOC); // Fetch results as associative array
        $stmt->close(); // Close the prepared statement
    } else {
        echo "Please enter a search term.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Recipes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional: Add CSS for styling -->
</head>
<body>
    <header>
        <h1>Recipe Search</h1>
    </header>
    <main>
        <!-- Back to Recipes Button -->
        <a href="recipes.php" class="btn">Back to Recipes</a>

        <section class="search-form">
            <h2>Find Your Favorite Recipes</h2>
            <form action="" method="POST">
                <input type="text" name="search_query" placeholder="Search by recipe name or ingredients..." required>
                <button type="submit">Search</button>
            </form>
        </section>
        <section class="search-results">
            <h2>Search Results</h2>
            <?php if (!empty($search_results)): ?>
                <ul>
                    <?php foreach ($search_results as $recipe): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($recipe['instructions']); ?></p>
                            <p><strong>Ingredients:</strong> <?php echo htmlspecialchars($recipe['ingredients']); ?></p>
                            <p><strong>Prep Time:</strong> <?php echo htmlspecialchars($recipe['prep_time']); ?> minutes</p>
                            <p><strong>Cook Time:</strong> <?php echo htmlspecialchars($recipe['cook_time']); ?> minutes</p>
                            <p><strong>Servings:</strong> <?php echo htmlspecialchars($recipe['servings']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($recipe['category']); ?></p>
                            <p><strong>Rating:</strong> <?php echo htmlspecialchars($recipe['rating']); ?> / 5</p>
                            <p><strong>Nutrition Info:</strong> <?php echo htmlspecialchars($recipe['nutrition_info']); ?></p>
                            <a href="recipe_detail.php?id=<?php echo htmlspecialchars($recipe['recipe_id']); ?>">View Recipe</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No recipes found. Try searching for something else!</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
