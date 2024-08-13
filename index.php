<?php
include 'includes/db.php';
include 'includes/auth.php';

require_login();

$sort = $_GET['sort'] ?? 'title'; // По умолчанию сортируем по заголовку

$valid_sorts = ['title', 'author', 'year'];
if (!in_array($sort, $valid_sorts)) {
    $sort = 'title';
}

$stmt = $pdo->prepare("SELECT * FROM books ORDER BY $sort");
$stmt->execute();
$books = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
</head>
<body>
    <h1>Library</h1>
    <a href="index.php?sort=title">Sort by Title</a> |
    <a href="index.php?sort=author">Sort by Author</a> |
    <a href="index.php?sort=year">Sort by Year</a>
    <h2>Books</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <strong><?php echo htmlspecialchars($book['title']); ?></strong> by <?php echo htmlspecialchars($book['author']); ?> (<?php echo htmlspecialchars($book['year']); ?>)
                <form method="post" action="rent.php">
                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
                    <select name="rental_period">
                        <option value="2 weeks">2 weeks</option>
                        <option value="1 month">1 month</option>
                        <option value="3 months">3 months</option>
                    </select>
                    <button type="submit">Rent</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
