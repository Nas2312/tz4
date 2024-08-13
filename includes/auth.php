<?php
session_start(); 

function require_login() {
    if (!isset($_SESSION['user_id'])) {  // Проверка, если в сессии нет идентификатора пользователя
        header('Location: login.php');  // Если нет, перенаправляем на страницу входа
        exit;  // Завершаем выполнение скрипта
    }
}

function require_admin() {
    require_login();  // Сначала проверяем, что пользователь авторизован
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {  // Проверка, если пользователь не администратор
        header('Location: index.php');  // Если нет, перенаправляем на главную страницу
        exit;  // Завершаем выполнение скрипта
    }
}
?>
