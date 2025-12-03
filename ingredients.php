<?php
session_start();
include('config/db.php'); 

$recipe_id = $_GET['id'];

$sql = "SELECT Ingredients.name, Ingredients.quantity, Ingredients.unit
        FROM Ingredients
        JOIN Menu ON Ingredients.ingredient_id = Menu.ingredient_id
        WHERE Menu.recipe_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$ingredients = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Ingredients</h1>
    </header>

    <main>
        <h2>Ingredients</h2>

        <?php if ($ingredients): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ingredient['name']); ?></td>
                            <td><?php echo htmlspecialchars($ingredient['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($ingredient['unit']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No ingredients found for this recipe.</p>
        <?php endif; ?>

        <a href="recipe_detail.php?id=<?php echo $recipe_id; ?>" class="btn">Back to Recipe</a>
    </main>
</body>
</html>
