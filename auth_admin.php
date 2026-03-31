<?php 
// 権限チェック
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // ログイン画面へ強制送還
    header('Location: index.php');
    exit;
}

?>