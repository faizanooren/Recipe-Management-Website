<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view this page.');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recipe_id'])) {
    $delete_recipe_id = $_POST['delete_recipe_id'];

    $delete_rate_sql = "DELETE FROM rate WHERE recipe_id = ?";
    $delete_rate_stmt = $conn->prepare($delete_rate_sql);
    $delete_rate_stmt->bind_param("i", $delete_recipe_id);
    $delete_rate_stmt->execute();

    $delete_menu_sql = "DELETE FROM menu WHERE recipe_id = ?";
    $delete_menu_stmt = $conn->prepare($delete_menu_sql);
    $delete_menu_stmt->bind_param("i", $delete_recipe_id);
    $delete_menu_stmt->execute();

    $delete_bookmarks_sql = "DELETE FROM bookmarks WHERE recipe_id = ?";
    $delete_bookmarks_stmt = $conn->prepare($delete_bookmarks_sql);
    $delete_bookmarks_stmt->bind_param("i", $delete_recipe_id);
    $delete_bookmarks_stmt->execute();

    $delete_sql = "DELETE FROM Recipe WHERE recipe_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $delete_recipe_id, $user_id);

    if ($delete_stmt->execute()) {
        $message = "Recipe deleted successfully.";
    } else {
        $message = "Failed to delete the recipe.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
        $update_sql = "UPDATE User SET password = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Example password hash
        $update_stmt->bind_param("si", $new_password, $user_id);

    if ($update_stmt->execute()) {
        $message = "Account updated successfully.";
    } else {
        $message = "Failed to update account.";
    }
    $_SESSION['message']=$message;
}

$recipe_sql = "SELECT * FROM Recipe WHERE user_id = ?";
$recipe_stmt = $conn->prepare($recipe_sql);
$recipe_stmt->bind_param("i", $user_id);
$recipe_stmt->execute();
$recipe_result = $recipe_stmt->get_result();
$recipes = $recipe_result->fetch_all(MYSQLI_ASSOC);

$user_sql = "SELECT * FROM User WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>My Recipes</h1>
        <a href="recipes.php" class="btn">Back to Home</a>
    </header>

    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <p><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']) ?></p>
        <?php endif; ?>

        <h2>Update Account</h2>
        <form action="profile.php" method="POST">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" value="" required>
            <button type="submit" name="update_account">Update</button>
        </form>

        <h2>Recipes</h2>
        <?php if ($recipes): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Prep Time</th>
                        <th>Cook Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recipes as $recipe): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($recipe['title']); ?></td>
                            <td><?php echo htmlspecialchars($recipe['category']); ?></td>
                            <td><?php echo htmlspecialchars($recipe['prep_time']); ?> min</td>
                            <td><?php echo htmlspecialchars($recipe['cook_time']); ?> min</td>
                            <td>
                                <form action="profile.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</button>
                                </form>
                                <a href="recipe_detail.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn">View</a>
                                <a href="recipe_update.php?id=<?php echo $recipe['recipe_id']; ?>" class="update_btn">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<style>
table {
    width: 100%;
    border-collapse: collapse;
}
table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}
table th {
    background-color: #f4f4f4;
}
.btn {
    padding: 6px 12px;
    background-color:rgb(13, 208, 3);
    color: #fff;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.update_btn {
    padding: 6px 12px;
    background-color:rgb(71, 38, 108);
    color: #fff;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.update_btn:hover {
    background-color:rgb(149, 111, 193);
}
.btn:hover {
    background-color:rgb(142, 215, 137);
}
</style>