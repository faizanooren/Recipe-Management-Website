<?php
session_start();

include('config/db.php');

$sql = "SELECT Recipe.* FROM Bookmarks 
        JOIN Recipe ON Bookmarks.recipe_id = Recipe.recipe_id 
        WHERE Bookmarks.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$saved_recipes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Recipes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Style for the "Back to Recipes" button positioned at the top left */
        .back-to-home {
            position: absolute;
            top: 20px; /* Distance from the top of the page */
            left: 20px; /* Distance from the left of the page */
            background-color: #647a70;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-to-home:hover {
            background-color: #00b35c;
        }

        /* Remove the header's navigation */
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
        }

        header h1 {
            margin: 0;
        }
    </style>
</head>
<body>
<header>
    <h1>Your Saved Recipes</h1>
</header>

<main>
    <section class="saved-recipes">
        <h2>Your Saved Recipes</h2>
        <?php if ($saved_recipes): ?>
            <ul>
                <?php foreach ($saved_recipes as $recipe): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
                        <a href="recipe_detail.php?id=<?php echo $recipe['recipe_id']; ?>">View Recipe</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have no saved recipes yet. Go explore and save your favorites!</p>
        <?php endif; ?>
    </section>
</main>

<!-- "Back to Recipes" button at the top left -->
<a href="recipes.php" class="back-to-home">Back to Recipes</a>

<footer>
    <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
</footer>
</body>
</html>
