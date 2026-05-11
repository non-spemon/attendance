<?php
session_start();
// 1.ログインチェック
require_once 'function.php';
checkLogin();
require_once 'db_connect.php';

// 2. セッションからユーザーIDと名前を取り出す
$userId   = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

// 3. 最新の1件を取得して「今の状態」を確認する
$sql = "SELECT status FROM attendance_logs 
        WHERE user_id = :user_id
        ORDER BY punched_at DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $userId]);
$last_log = $stmt->fetch();

// データがなければ「退勤中（check-out）」とみなす
$current_status = $last_log['status'] ?? 'check-out';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require 'common_head.php'; ?>
    <title>打刻画面</title>
</head>
<body>
    <div class="container_punch">
        <div class="status">
            <h1>勤怠管理してね</h1>
            
            <p>こんにちは、<?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>さん！</p>
            
            <p>現在の状態：<strong><?php echo ($current_status === 'check-in') ? '出勤中' : '退勤中'; ?></strong></p>
            
            <hr>
        </div>
        <form action="punch_process.php" method="POST">
            <?php if ($current_status === 'check-out'): ?>
                <button type="submit" name="action" value="in" class="in">出勤</button>
            <?php else: ?>
                <button type="submit" name="action" value="out" class="out">退勤</button>
            <?php endif; ?> </form>

        <br>
        <a class="logout_btn" href="logout.php">ログアウト</a>
    </div>
</body>
</html>