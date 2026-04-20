<?php
session_start();

// 1. セッションの中身（ロッカーの中身）をすべて空にする
$_SESSION = array();

// 2. セッション自体（ロッカーの鍵）を壊す
session_destroy();

// 3. ログイン画面へ戻す
header('Location: index.php');
exit;

?>