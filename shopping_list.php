<?php
session_start();
include('config/db.php');

$user_id = $_SESSION['user_id'];

// Check if the user already has a shopping list
$stmt = $conn->prepare("SELECT list_no FROM ShoppingList WHERE user_id = ?");
$stmt->bind_param("i", $user_id);  // Bind user ID as an integer
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    // creating a new list
    $stmt = $conn->prepare("INSERT INTO ShoppingList (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);  // Bind user ID as an integer
    $stmt->execute();
    $list_no = $stmt->insert_id;
} else {
    // If shopping list exists, get the list number
    $stmt->bind_result($list_no);
    $stmt->fetch();
}

// adding and deleting items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $item = $_POST['item'] ?? '';

    if ($action === 'add_item' && !empty($item)) {
        // Add item to the shopping list
        $stmt = $conn->prepare("INSERT INTO Items (list_no, item) VALUES (?, ?)");
        $stmt->bind_param("is", $list_no, $item);  // Bind list_no as integer and item as string
        $stmt->execute();
    } elseif ($action === 'delete_item' && !empty($item)) {
        // Remove item from the shopping list
        $stmt = $conn->prepare("DELETE FROM Items WHERE list_no = ? AND item = ?");
        $stmt->bind_param("is", $list_no, $item);  // Bind list_no as integer and item as string
        $stmt->execute();
    }
}

// Fetch items in the shopping list
$stmt = $conn->prepare("SELECT item FROM Items WHERE list_no = ?");
$stmt->bind_param("i", $list_no);  // Bind list_no as integer
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);  // Fetch all items as an associative array

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .back-to-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #647a70;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-to-home:hover {
            background-color: #00b35c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Shopping List</h1>
    </header>
    <main>
        <form method="POST">
            <input type="text" name="item" placeholder="Add an item..." required>
            <button type="submit" name="action" value="add_item">Add</button>
        </form>

        <h2>Items</h2>
        <ul>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['item']); ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="item" value="<?php echo htmlspecialchars($item['item']); ?>">
                            <button type="submit" name="action" value="delete_item">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No items in your list.</li>
            <?php endif; ?>
        </ul>

        <a href="recipes.php" class="back-to-home">Back to Home</a>
    </main>
</body>
</html>
