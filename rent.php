<?php
include 'includes/db.php';
include 'includes/auth.php';

require_login();

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];
$rental_period = $_POST['rental_period'];

// Массив сопоставления периода аренды с SQL-интервалом
$intervals = [
    '2 weeks' => '2 WEEK',
    '1 month' => '1 MONTH',
    '3 months' => '3 MONTH'
];

if (!isset($intervals[$rental_period])) {
    die('Invalid rental period.');
}

// Проверка доступности книги
$stmt = $pdo->prepare("SELECT status FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if ($book['status'] != 'available') {
    die('Book is not available.');
}

// Обновление статуса книги и добавление записи об аренде
$pdo->beginTransaction();

$stmt = $pdo->prepare("
    INSERT INTO rentals (user_id, book_id, rental_period, return_date) 
    VALUES (?, ?, ?, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$intervals[$rental_period]}))
");
$stmt->execute([$user_id, $book_id, $rental_period]);

$stmt = $pdo->prepare("UPDATE books SET status = 'rented' WHERE id = ?");
$stmt->execute([$book_id]);

$pdo->commit();

header('Location: index.php');
?>
