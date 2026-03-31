<?php
session_start();
//  データベース接続
require_once 'db_connect.php';
require_once 'auth_admin.php';

// 送られてきたデータを受け取る
$user_id = $_GET['user_id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$user_id || !$date) {
    exit('ユーザーIDまたは日付が指定されていません。');
}

// データベースから取得
$check_in_time = '';
$check_out_time = '';

try {
    $stmt = $pdo->prepare("
        SELECT * FROM attendance_logs 
        WHERE user_id = :user_id 
        AND DATE(punched_at) = :date
    ");
    $stmt->execute([':user_id' => $user_id, ':date' => $date]);
    $all_logs = $stmt->fetchAll();

    if (empty($all_logs)) {
        exit('指定された日のデータが見つかりません。');
    }

    // 出勤と退勤の時間を変数に仕分け
    foreach ($all_logs as $log) {
        // 余計な空白を消して、小文字で比較する
        $status_value = strtolower(trim($log['status']));
        
        if ($status_value === 'check-in') {
            $check_in_time = date('H:i', strtotime($log['punched_at']));
        } 
        elseif ($status_value === 'check-out') {
            $check_out_time = date('H:i', strtotime($log['punched_at']));
        }
    }
} catch (PDOException $e) {
    exit('データ取得エラー：' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>打刻データの編集</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="container">
    <h1>打刻データの編集</h1>
    <form action="update.php" method="POST" onsubmit="return confirm('本当に更新しますか？')">
        
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="original_date" value="<?php echo htmlspecialchars($date); ?>">

        <div class="edit-group">
            <label>日付</label>
            <input type="date" name="new_date" value="<?php echo htmlspecialchars($date); ?>">
        </div>

        <div class="edit-group">
            <label>出勤時間</label>
            <input type="time" name="check_in_time" value="<?php echo $check_in_time; ?>">
        </div>
        
        <div class="edit-group">
            <label>退勤時間</label>
            <input type="time" name="check_out_time" value="<?php echo $check_out_time; ?>">
        </div>

        <br>
        <button class="update" type="submit">更新する</button>
        <a class="cancel" href="admin_dashboard.php">キャンセル</a>
    </form>
</div>
</body>
</html>