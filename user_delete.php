<?php
session_start();
//  データベース接続
require_once 'db_connect.php';
require_once 'auth_admin.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {
    try {
        if ($id === (int)$_SESSION['user_id']) {
            header('Location: user_list.php');
            exit;
        }

        // トランザクションを開始します
        $pdo->beginTransaction();

        // 1. まず、そのユーザーに紐付く「すべての」打刻履歴を削除する
        $sql_logs = "DELETE FROM attendance_logs WHERE user_id = :id";
        $stmt_logs = $pdo->prepare($sql_logs);
        $stmt_logs->execute([':id' => $id]);

        // 2. その後、ユーザー本人を削除する
        $sql_user = "DELETE FROM users WHERE id = :id LIMIT 1";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->execute([':id' => $id]);

        // 【ここが重要！】すべて成功したら確定
        $pdo->commit();

        header('Location: user_list.php?message=deleted');
        exit;

    } catch (PDOException $e) {
        // もし途中で失敗したら、開始前の状態に巻き戻す
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        // デバッグ用
         exit('エラー内容: ' . $e->getMessage()); 
        // header('Location: user_list.php?error=failed');
        exit;
    }
}