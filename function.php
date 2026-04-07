<?php
// 一般ユーザー門番（打刻画面用）
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

// ② 「管理者（admin）でなければダメ」な門番（管理者画面用）
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

?>
