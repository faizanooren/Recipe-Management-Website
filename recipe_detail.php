<?php
session_start();
include('config/db.php'); 

if (!isset($_GET['id'])) {
    die('Recipe ID not specified.');
}

$recipe_id = $_GET['id'];
$user_id = $_SESSION['user_id']; 

// Handle form submission for rating and comment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle comment and rating submission
    if (isset($_POST['comment']) && isset($_POST['rating'])) {
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];
        $date = date('Y-m-d'); // Current date
        
        // Insert the rating and comment into the Rate table
        $insert_sql = "INSERT INTO Rate (date, comment, rating, user_id, recipe_id) 
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssdis", $date, $comment, $rating, $user_id, $recipe_id);
        $insert_stmt->execute();
        
        // Optional: Handle success message or redirection
        $message = "Your comment and rating have been submitted!";
    }
    
    // Handle comment deletion
    if (isset($_POST['delete_comment_id'])) {
        $comment_id = $_POST['delete_comment_id'];
        
        // Check if the comment belongs to the logged-in user
        $delete_sql = "DELETE FROM Rate WHERE id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $comment_id, $user_id);
        $delete_stmt->execute();
        
        // Optional: Success message for comment deletion
        $message = "Your comment has been deleted!";
    }

    // Handle save/unsave recipe action
    if (isset($_POST['save_recipe'])) {
        // Check if recipe is already bookmarked
        $check_sql = "SELECT * FROM Bookmarks WHERE user_id = ? AND recipe_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $recipe_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Recipe is already bookmarked, so remove it
            $delete_sql = "DELETE FROM Bookmarks WHERE user_id = ? AND recipe_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $user_id, $recipe_id);
            $delete_stmt->execute();
            $message = "Recipe has been removed from bookmarks.";
        } else {
            // Recipe is not bookmarked, so add it to bookmarks
            $insert_sql = "INSERT INTO Bookmarks (user_id, recipe_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ii", $user_id, $recipe_id);
            $insert_stmt->execute();
            $message = "Recipe has been saved to bookmarks.";
        }
    }
}

// Fetch recipe details, including the uploader's name
$recipe_sql = "SELECT Recipe.*, User.name AS uploader_name 
               FROM Recipe 
               JOIN User ON Recipe.user_id = User.user_id 
               WHERE recipe_id = ?";
$recipe_stmt = $conn->prepare($recipe_sql);
$recipe_stmt->bind_param("i", $recipe_id);
$recipe_stmt->execute();
$recipe_result = $recipe_stmt->get_result();
$recipe = $recipe_result->fetch_assoc();

if (!$recipe) {
    die('Recipe not found.');
}

// Fetch comments and ratings for the recipe
$comment_sql = "SELECT Rate.id, Rate.rating, Rate.comment, Rate.date, User.name AS username, Rate.user_id 
                FROM Rate 
                JOIN User ON Rate.user_id = User.user_id 
                WHERE Rate.recipe_id = ? 
                ORDER BY Rate.date DESC";
$comment_stmt = $conn->prepare($comment_sql);
$comment_stmt->bind_param("i", $recipe_id);
$comment_stmt->execute();
$comments_result = $comment_stmt->get_result();
$comments = $comments_result->fetch_all(MYSQLI_ASSOC);

// Average rating
$average_sql = "SELECT AVG(rating) AS average_rating FROM Rate WHERE recipe_id = ?";
$average_stmt = $conn->prepare($average_sql);
$average_stmt->bind_param("i", $recipe_id);
$average_stmt->execute();
$average_result = $average_stmt->get_result();
$average_rating = $average_result->fetch_assoc()['average_rating'];

// Check if the recipe is bookmarked by the user
$bookmark_check_sql = "SELECT * FROM Bookmarks WHERE user_id = ? AND recipe_id = ?";
$bookmark_check_stmt = $conn->prepare($bookmark_check_sql);
$bookmark_check_stmt->bind_param("ii", $user_id, $recipe_id);
$bookmark_check_stmt->execute();
$bookmark_check_result = $bookmark_check_stmt->get_result();
$is_bookmarked = $bookmark_check_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Recipe Manager</h1>
    </header>

    <!-- "Back to Recipes" button at the top -->
    <a href="recipes.php" class="btn">Back to Recipes</a>

    <main>
        <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
        <p><strong>Uploaded by:</strong> <?php echo htmlspecialchars($recipe['uploader_name']); ?></p>

        <!-- Display recipe image if exists -->
        <?php if (!empty($recipe['recipe_image'])): ?>
            <img src="images/<?=htmlspecialchars($recipe['recipe_image'])?>" alt="Recipe Image" class="recipe-image" width="250px">
        <?php else: ?>
            <p>No image available for this recipe.</p>
        <?php endif; ?>

        <p><strong>Category:</strong> <?php echo htmlspecialchars($recipe['category']); ?></p>
        <p><strong>Instructions:</strong> <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
        <p><strong>Prep Time:</strong> <?php echo htmlspecialchars($recipe['prep_time']); ?> minutes</p>
        <p><strong>Cook Time:</strong> <?php echo htmlspecialchars($recipe['cook_time']); ?> minutes</p>
        <p><strong>Servings:</strong> <?php echo htmlspecialchars($recipe['servings']); ?></p>
        <p><strong>Average Rating:</strong> <?php echo $average_rating ? round($average_rating, 1) : "No ratings yet"; ?></p>

        <!-- View Ingredients button -->
        <a href="ingredients.php?id=<?php echo $recipe_id; ?>" class="btn">View Ingredients</a>

        <!-- Save Recipe Button -->
        <form action="recipe_detail.php?id=<?php echo $recipe_id; ?>" method="POST">
            <button type="submit" name="save_recipe">
                <?php echo $is_bookmarked ? "Remove from Bookmarks" : "Save to Bookmarks"; ?>
            </button>
        </form>

        <form action="recipe_detail.php?id=<?php echo $recipe_id; ?>" method="POST">
            <h3>Leave a Comment</h3>
            <textarea name="comment" placeholder="Your comment..." required></textarea><br>
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br>
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <button type="submit">Submit Comment</button>
        </form>

        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h3>Comments & Ratings</h3>
        <?php if ($comments): ?>
            <ul>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        <p><strong>Rating:</strong> <?php echo htmlspecialchars($comment['rating']); ?> | 
                        <strong>Date:</strong> <?php echo htmlspecialchars($comment['date']); ?></p>

                        <!-- Display the delete button if the comment was made by the logged-in user -->
                        <?php if ($comment['user_id'] == $user_id): ?>
                            <form action="recipe_detail.php?id=<?php echo $recipe_id; ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<style>
.recipe-image {
    width: 100%;
    max-width: 600px;
    height: auto;
    margin: 20px 0;
}
</style>
