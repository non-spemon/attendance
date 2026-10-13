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
    exit('復元に必要な情報が不足しています。');
}

try {
    // deleted_atをnullにして戻す
    $sql = "UPDATE attendance_logs
            SET deleted_at = NULL
            WHERE user_id = :user_id 
            AND DATE(punched_at) = :date
            AND deleted_at IS NOT NULL";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':date'    => $date
    ]);

    header('Location: admin_dashboard.php?message=restored');
    exit;

} catch (PDOException $e) {
    handle_error('復元エラーが発生しました。：' . $e->getMessage());
}