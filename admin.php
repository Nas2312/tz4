<?php
include 'includes/db.php';
include 'includes/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Обновление книги
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, category = ?, year = ?, price = ?, status = ? WHERE id = ?");
        $stmt->execute([$_POST['title'], $_POST['author'], $_POST['category'], $_POST['year'], $_POST['price'], $_POST['status'], $_POST['id']]);
    } elseif (isset($_POST['add'])) {
        // Добавление новой книги
        $stmt = $pdo->prepare("INSERT INTO books (title, author, category, year, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['author'], $_POST['category'], $_POST['year'], $_POST['price']]);
    }
}

$books = $pdo->query("SELECT * FROM books")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <h2>Add New Book</h2>
    <form method="post" action="admin.php">
        <input type="hidden" name="add" value="1">
        Title: <input type="text" name="title" required><br>
        Author: <input type="text" name="author" required><br>
        Category: <input type="text" name="category"><br>
        Year: <input type="number" name="year"><br>
        Price: <input type="text" name="price" required><br>
        <button type="submit">Add Book</button>
    </form>
    <h2>Books List</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo htmlspecialchars($book['title']); ?> by <?php echo htmlspecialchars($book['author']); ?> (<?php echo htmlspecialchars($book['year']); ?>)
                <form method="post" action="admin.php" style="display:inline;">
                    <input type="hidden" name="update" value="1">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id']); ?>">
                    Title: <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    Author: <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    Category: <input type="text" name="category" value="<?php echo htmlspecialchars($book['category']); ?>">
                    Year: <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>">
                    Price: <input type="text" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required>
                    Status:
                    <select name="status">
                        <option value="available" <?php if ($book['status'] == 'available') echo 'selected'; ?>>Available</option>
                        <option value="rented" <?php if ($book['status'] == 'rented') echo 'selected'; ?>>Rented</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
