<?php
session_start();
//  データベース接続
require_once 'db_connect.php';
require_once 'auth_admin.php';

//  URLからIDを取得
$id = $_POST['id'] ?? null;

if (!$id) {
    exit('削除するIDが指定されていません。');
}

try {
    // データベースから削除を実行
    // WHERE句を忘れると全データ消えるので注意！
    $sql = "DELETE FROM attendance_logs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // 4. 完了したら管理者ダッシュボードに戻る
    header('Location: admin_dashboard.php?message=deleted');
    exit;

} catch (PDOException $e) {
    exit('削除エラーが発生しました：' . $e->getMessage());
}