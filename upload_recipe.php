<?php
// Start the session
session_start();

include('config/db.php'); 


// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload a recipe.");
}

// Retrieve the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Collect recipe data
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $instructions = $conn->real_escape_string($_POST['instructions']);
    $prep_time = (int)$_POST['prep_time'];
    $cook_time = (int)$_POST['cook_time'];
    $servings = (int)$_POST['servings'];
    $category = $conn->real_escape_string($_POST['category']);
    $nutrition_info = $conn->real_escape_string($_POST['nutrition_info']);

    // Handle image upload
    $target_dir = "images/"; // Directory to save uploaded images
    $image_name = basename($_FILES["recipe"]["name"]);
    $target_file = $target_dir . $image_name;
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES["recipe"]["tmp_name"]);
    if ($check === false) {
        echo "<p>File is not an image.</p>";
        $upload_ok = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["recipe"]["size"] > 2000000) {
        echo "<p>Sorry, your file is too large.</p>";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if (!in_array($image_file_type, ["jpg", "png", "jpeg", "gif"])) {
        echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        $upload_ok = 0;
    }

    // Check if upload is allowed
    if ($upload_ok === 0) {
        echo "<p>Sorry, your file was not uploaded.</p>";
    } else {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["recipe"]["tmp_name"], $target_file)) {
            // Insert recipe into the database
            $sql = "INSERT INTO Recipe (user_id, title, description, instructions, prep_time, cook_time, servings, category, nutrition_info, recipe_image) 
                    VALUES ('$user_id', '$title', '$description', '$instructions', '$prep_time', '$cook_time', '$servings', '$category', '$nutrition_info', '$image_name')";

            if ($conn->query($sql) === TRUE) {
                $recipe_id = $conn->insert_id; // Get the ID of the newly inserted recipe

                // Insert ingredients into the database
                if (!empty($_POST['ingredient_name']) && is_array($_POST['ingredient_name'])) {
                    $ingredient_names = $_POST['ingredient_name'];
                    $ingredient_quantities = $_POST['ingredient_quantity'];
                    $ingredient_units = $_POST['ingredient_unit'];

                    foreach ($ingredient_names as $index => $name) {
                        $ingredient_name = $conn->real_escape_string($name);
                        $ingredient_quantity = (float)$ingredient_quantities[$index];
                        $ingredient_unit = $conn->real_escape_string($ingredient_units[$index]);

                        // Insert each ingredient into the Ingredients table
                        $sql_ingredient = "INSERT INTO Ingredients (name, quantity, unit) 
                                           VALUES ('$ingredient_name', '$ingredient_quantity', '$ingredient_unit')";

                        $conn->query($sql_ingredient);

                        // Get the ingredient_id of the newly inserted ingredient
                        $ingredient_id = $conn->insert_id;

                        // Insert the recipe-ingredient relationship into the Menu table
                        $sql_menu = "INSERT INTO Menu (ingredient_id, recipe_id) 
                                     VALUES ('$ingredient_id', '$recipe_id')";

                        $conn->query($sql_menu);
                    }
                }

                echo "<p>Recipe uploaded successfully!</p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Recipe</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to styles.css -->
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="recipes.php" style="text-decoration: none; color: #000;">
                <button style="padding: 10px 15px; background-color: #f0f0f0; border: 1px solid #ccc; cursor: pointer;">Back to Recipes</button>
            </a>
            <h1>Upload Your Recipe</h1>
        </div>
    </header>
    <main>
        <section class="upload-recipe">
            <form action="upload_recipe.php" method="POST" enctype="multipart/form-data">
                <!-- Recipe Title -->
                <label for="title">Recipe Title:</label><br>
                <input type="text" id="title" name="title" required><br><br>

                <!-- Description -->
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="3" required></textarea><br><br>

                <!-- Instructions -->
                <label for="instructions">Instructions:</label><br>
                <textarea id="instructions" name="instructions" rows="5" required></textarea><br><br>

                <!-- Preparation Time -->
                <label for="prep_time">Preparation Time (minutes):</label><br>
                <input type="number" id="prep_time" name="prep_time" required><br><br>

                <!-- Cooking Time -->
                <label for="cook_time">Cooking Time (minutes):</label><br>
                <input type="number" id="cook_time" name="cook_time" required><br><br>

                <!-- Servings -->
                <label for="servings">Servings:</label><br>
                <input type="number" id="servings" name="servings" required><br><br>

                <!-- Category -->
                <label for="category">Category:</label><br>
                <input type="text" id="category" name="category" required><br><br>

                <!-- Nutrition Information -->
                <label for="nutrition_info">Nutrition Information:</label><br>
                <textarea id="nutrition_info" name="nutrition_info" rows="3"></textarea><br><br>

                <!-- Recipe Image -->
                <label for="image">Recipe Image:</label><br>
                <input type="file" name="recipe" id="recipe" required><br><br>

                <!-- Ingredients Section -->
                <label for="ingredient_name[]">Ingredient Name:</label><br>
                <input type="text" name="ingredient_name[]" required><br><br>
                <label for="ingredient_quantity[]">Quantity:</label><br>
                <input type="number" step="any" name="ingredient_quantity[]" required><br><br>
                <label for="ingredient_unit[]">Unit:</label><br>
                <input type="text" name="ingredient_unit[]" required><br><br>

                <!-- Add Ingredient Button -->
                <button type="button" onclick="addIngredient()">Add Another Ingredient</button><br><br>

                <!-- Submit Button -->
                <button type="submit" name="submit">Upload Recipe</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Manager. All rights reserved.</p>
    </footer>

    <script>
        function addIngredient() {
            let ingredientDiv = document.createElement('div');
            ingredientDiv.innerHTML = `
                <label for="ingredient_name[]">Ingredient Name:</label><br>
                <input type="text" name="ingredient_name[]" required><br><br>
                <label for="ingredient_quantity[]">Quantity:</label><br>
                <input type="number" step="any" name="ingredient_quantity[]" required><br><br>
                <label for="ingredient_unit[]">Unit:</label><br>
                <input type="text" name="ingredient_unit[]" required><br><br>
            `;
            document.querySelector('form').insertBefore(ingredientDiv, document.querySelector('form button'));
        }
    </script>
</body>
</html>
