<?php
require_once 'check_login.php';
require_once 'db_connect.php';

$action = $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];


try {
    $sql_check = "SELECT status FROM attendance_logs
                  WHERE user_id = :user_id
                  ORDER BY punched_at DESC LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':user_id' => $userId]);
    $last_log = $stmt_check->fetch();
    $current_status = $last_log['status'] ?? 'check-out';

    if ($action === 'in' && $current_status === 'check-in'){
        exit("エラー: すでに記録されています（出勤中）");
    }
    if ($action === 'out' && $current_status === 'check-out'){
        exit("エラー : 出勤データが見つかりません");
    }

    if ($action === 'in') {
        // 出勤処理
        $sql = "INSERT INTO attendance_logs (user_id, status, punched_at) VALUES (:user_id, 'check-in', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        echo "<script>
        alert('おはよう。今日もよろしくね。');
        window.location.href = 'punch_screen.php';
        </script>";
        exit;
    } elseif ($action === 'out') {
        // 退勤処理
        $sql = "INSERT INTO attendance_logs (user_id, status, punched_at) VALUES (:user_id, 'check-out', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        // 退勤を記録しました！alert表現
        echo "<script>
        alert('退勤を記録しました！お疲れ様でした。');
        window.location.href = 'index.php'; 
        </script>";
        exit;
    }
    
} catch (PDOException $e) {
    // 4. 出勤・退勤どちらでエラーが起きてもここでキャッチ！
    exit("エラーが発生しました: " . $e->getMessage());
}

