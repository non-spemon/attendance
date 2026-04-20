<?php
// ログイン画面の中のさらに警備員
session_start();
if (!isset($_SESSION['user_id'])) {
    // ログインしていなければ、ログイン画面に強制的に戻す
    header('Location: index.php');
    exit;
}

?>

