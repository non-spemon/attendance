<?php
session_start();
require_once 'db_connect.php';

// 1. 権限チェック（管理者以外はアクセス不可）
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('権限がありません。');
}

// 2. URLからIDを取得
$id = $_GET['id'] ?? null;

if (!$id) {
    exit('削除するIDが指定されていません。');
}

try {
    // 3. データベースから削除を実行
    // WHERE句を忘れると全データ消えるので注意！
    $sql = "DELETE FROM attendance_logs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // 4. 完了したら管理者ダッシュボードに戻る
    // メッセージを付けておくと親切です
    header('Location: admin_dashbord.php?message=deleted');
    exit;

} catch (PDOException $e) {
    exit('削除エラーが発生しました：' . $e->getMessage());
}