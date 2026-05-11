<?php
session_start();
require_once 'db_connect.php';
require_once 'function.php';
checkAdmin();

// POSTで送られてきた「ユーザーID」と「日付」を取得
$user_id = $_POST['user_id'] ?? null;
$date = $_POST['date'] ?? null;

// 両方のデータがない場合はエラー
if (!$user_id || !$date) {
    exit('削除に必要な情報が不足しています。');
}

try {
    // 【修正ポイント】
    // 「特定のユーザー」の「特定の日付」のログをすべて削除するSQL
    $sql = "DELETE FROM attendance_logs 
            WHERE user_id = :user_id 
            AND DATE(punched_at) = :date";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':date'    => $date
    ]);

    header('Location: admin_dashboard.php?message=deleted');
    exit;

} catch (PDOException $e) {
    handle_error('削除エラーが発生しました。：' . $e->getMessage());
}