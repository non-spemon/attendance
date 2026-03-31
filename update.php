<?php
session_start();
//  データベース接続
require_once 'db_connect.php';
require_once 'auth_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから届いたデータ
    $user_id = $_POST['user_id'];
    $original_date = $_POST['original_date']; // 修正前の日付
    $new_date = $_POST['new_date'];           // 修正後の日付
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];

    try {
        $pdo->beginTransaction(); // 失敗に備えてトランザクション開始

        // 1. 出勤データの更新
        if (!empty($check_in_time)) {
            $stmt = $pdo->prepare("
                UPDATE attendance_logs 
                SET punched_at = :new_punched, status = 'check-in'
                WHERE user_id = :user_id 
                AND DATE(punched_at) = :old_date 
                AND status = 'check-in'
            ");
            $stmt->execute([
                ':new_punched' => $new_date . ' ' . $check_in_time . ':00',
                ':user_id' => $user_id,
                ':old_date' => $original_date
            ]);
        }

        // 2. 退勤データの更新
        if (!empty($check_out_time)) {
            $stmt = $pdo->prepare("
                UPDATE attendance_logs 
                SET punched_at = :new_punched, status = 'check-out'
                WHERE user_id = :user_id 
                AND DATE(punched_at) = :old_date 
                AND status = 'check-out'
            ");
            $stmt->execute([
                ':new_punched' => $new_date . ' ' . $check_out_time . ':00',
                ':user_id' => $user_id,
                ':old_date' => $original_date
            ]);
        }

        $pdo->commit(); // すべて成功したら確定

        // ダッシュボードへ戻る
        header('Location: admin_dashboard.php?message=success');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack(); // どこかで失敗したら元に戻す
        exit('更新エラーが発生しました：' . $e->getMessage());
    }
}