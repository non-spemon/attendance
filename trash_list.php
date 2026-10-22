<?php
//  セッション開始
session_start();
//  データベース接続
require_once 'db_connect.php';
require_once 'function.php';
checkAdmin();

// データ取得
$logs = []; 
try {
     $sql = "SELECT 
                users.id AS user_id,
                users.name AS 名前,
                DATE(attendance_logs.punched_at) AS 打刻日,
                attendance_logs.deleted_at AS 削除された日時,
                -- 出勤時間を探して抽出
                MIN(CASE WHEN attendance_logs.status = 'check-in' THEN attendance_logs.punched_at END) AS 出勤時間,
                -- 退勤時間を探して抽出
                MAX(CASE WHEN attendance_logs.status = 'check-out' THEN attendance_logs.punched_at END) AS 退勤時間
            FROM attendance_logs
            JOIN users ON attendance_logs.user_id = users.id
            WHERE attendance_logs.deleted_at IS NOT NULL -- 削除されているものに絞る
            GROUP BY user_id, 打刻日, attendance_logs.deleted_at -- ユーザー、打刻日、削除日でまとめる
            ORDER BY 打刻日 DESC, 出勤時間 DESC";

    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll();
} catch (PDOException $e) {
    handle_error('データ取得エラーが発生しました：' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require 'common_head.php'; ?>
    <title>管理者画面：ゴミ箱</title>
</head>
<body>
    <div class="head">
        <h1>ゴミ箱</h1>
        <?php displayErrorMessage(); ?>
        <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?> 
        <p style="color: red; font-weight: bold;">データを削除しました。</p>
        <?php endif; ?> 

        <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
            <p style="color: green; font-weight: bold;">データを更新しました。</p>
        <?php endif; ?>
        <a class="user_btn" href="admin_dashboard.php">戻る</a>
    </div>
    <div class="table-container">
    <table >
        <thead>
            <tr>
                <th>名前</th>
                <th>状態</th>
                <th>日時</th>
                <th>削除日時</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5">データがありません。</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    
                        
            <tr>
                <td><?php echo htmlspecialchars($log['名前']); ?></td>
                <td>
                    <?php 
                    if (!empty($log['出勤時間']) && empty($log['退勤時間'])) {
                        echo '<span style="color: #caad2b; font-weight: bold;">出勤中</span>';
                    } elseif (!empty($log['出勤時間']) && !empty($log['退勤時間'])) {
                        echo '退勤済';
                    } else {
                        echo '---';
                    }
                    ?>
                    <br>
                    <small>
                        (<?php echo $log['出勤時間'] ? date('H:i', strtotime($log['出勤時間'])) : '--:--'; ?> ～ 
                        <?php echo $log['退勤時間'] ? date('H:i', strtotime($log['退勤時間'])) : '--:--'; ?>)
                    </small>
                </td>
                <td><?php echo htmlspecialchars($log['打刻日']); ?></td>
                <td><?php echo htmlspecialchars($log['削除された日時']); ?></td>

                <td class="btn"> 
                    <!-- <a class="edit" href="edit.php?user_id=<?php echo $log['user_id']; ?>&date=<?php echo $log['打刻日']; ?>">編集</a> -->
                    <form action="recover.php" method="POST" style="display:inline;" onsubmit="return confirm('このデータを復元します');">
                        <!-- user_id を送る -->
                        <input type="hidden" name="user_id" value="<?php echo $log['user_id']; ?>">
                        <!-- 打刻日（date）を送る -->
                        <input type="hidden" name="date" value="<?php echo $log['打刻日']; ?>">
                        <!-- 削除日時（deleted_at）送る -->
                        <input type="hidden" name="deleted_at" value="<?php echo $log['削除された日時']; ?>">
                        <button type="submit" class="delete">復元</button>
                    </form>
                </td>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</body>
</html>