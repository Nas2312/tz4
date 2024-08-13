<?php
include 'includes/db.php';

// Получаем текущую дату
$current_date = new DateTime();

// Получаем аренды, которые истекают в течение 7 дней
$stmt = $pdo->prepare("
    SELECT rentals.*, users.username, books.title
    FROM rentals
    JOIN users ON rentals.user_id = users.id
    JOIN books ON rentals.book_id = books.id
    WHERE return_date <= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 7 DAY) AND return_date > CURRENT_TIMESTAMP
");
$stmt->execute();
$rentals = $stmt->fetchAll();

foreach ($rentals as $rental) {
    // Отправка уведомлений пользователю
    $to = $rental['username'] . '@example.com'; // Здесь должен быть правильный email
    $subject = "Reminder: Your rental for " . $rental['title'] . " is expiring soon";
    $message = "Dear " .
