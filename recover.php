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

try{
$sql_check = "SELECT COUNT(*) FROM attendance_logs
              WHERE user_id = :user_id
              AND DATE(punched_at) = :date
              AND deleted_at IS NULL";

$stmt =$pdo->prepare($sql_check);
$stmt->execute([
    ':user_id' => $user_id,
    ':date'    => $date
]);
$activeCount = $stmt->fetchColumn();

// すでに有効なデータがある場合は、重複を避けるために復元をブロックする
if ($activeCount > 0) {
    $_SESSION['error'] = "この日にはすでに新しい有効な打刻データが存在しています。重複を防ぐため、復元できません。";
    header("Location: admin_dashboard.php");
    exit; 
}

    // 問題なければdeleted_atをnullにして戻す
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