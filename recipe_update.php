<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view this page.');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_recipe'])) {
    $recipe_id = $_POST['recipe_id'];
    $title = $_POST['title'];
    $instructions = $_POST['instructions'];
    $prep_time = (int)$_POST['prep_time'];
    $cook_time = (int)$_POST['cook_time'];
    $category = $_POST['category'];
    $nutrition_info = $_POST['nutrition_info'];
    $description = $_POST['description'];
    $servings = (int)$_POST['servings'];

    $update_sql = "UPDATE recipe SET title = ?, instructions = ?, prep_time = ?, cook_time = ?, category = ?, servings = ?,nutrition_info = ?, description = ? WHERE recipe_id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param("ssiisissii", $title, $instructions, $prep_time, $cook_time, $category,$servings,$nutrition_info,$description,$recipe_id, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Recipe updated successfully.";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to update the recipe.";
    }
}

if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];
    $recipe_sql = "SELECT * FROM Recipe WHERE recipe_id = ? AND user_id = ?";
    $recipe_stmt = $conn->prepare($recipe_sql);
    $recipe_stmt->bind_param("ii", $recipe_id, $user_id);
    $recipe_stmt->execute();
    $recipe = $recipe_stmt->get_result()->fetch_assoc();

    if (!$recipe) {
        die('Recipe not found or you do not have permission to edit it.');
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Recipe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Update Recipe</h1>
        <a href="profile.php" class="btn">Back to My Recipes</a>
    </header>

    <main>
    <?php if (isset($_SESSION['message'])): ?>
        <p><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']) ?></p>
    <?php endif; ?>

    <h2>Edit Recipe:</h2>
    <h4> <br> <?php echo htmlspecialchars($recipe['title']); ?></h4>
    <form action="recipe_update.php" method="POST">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
        
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
        
        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="4" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
        <label for="nutrition_info">Nutrition Info:</label>
        <textarea id="nutrition_info" name="nutrition_info" rows="3" required><?php echo htmlspecialchars($recipe['nutrition_info']); ?></textarea>
        <label for="description"></label>
        <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
        
        <label for="prep_time">Prep Time (min):</label>
        <input type="number" id="prep_time" name="prep_time" value="<?php echo htmlspecialchars($recipe['prep_time']); ?>" required>
        
        <label for="cook_time">Cook Time (min):</label>
        <input type="number" id="cook_time" name="cook_time" value="<?php echo htmlspecialchars($recipe['cook_time']); ?>" required>
        
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($recipe['category']); ?>" required>

        <label for="servings">Servings:</label>
        <input type="number" id="servings" name="servings" value="<?php echo htmlspecialchars($recipe['servings']); ?>" required>
  
  
        <button type="submit" name="update_recipe">Update Recipe</button>
    </form>
</main>
</body>
</html>
<style>

body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9;
    color: #333;
}

h1 {
    margin: 0;
}

main {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
textarea,
input[type="number"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
textarea:focus,
input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
}

button {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    margin-left: 150px;
    margin-right: 150px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

p {
    color: red;
    font-weight: bold;
}

</style>