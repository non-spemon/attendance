<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

try {
    $sql = "SELECT id, name, email, role FROM users ORDER BY id ASC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    exit('データ取得エラー：' . $e->getMessage());
}

?>

<?php
session_start();
require_once 'db_connect.php';

// 管理者以外はアクセス禁止
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// ユーザー一覧を取得（自分以外を表示する場合などはWHERE句を足します）
try {
    $sql = "SELECT id, name, email, role FROM users ORDER BY id ASC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    exit('データ取得エラー：' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スタッフ管理一覧</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon16.ico">
    <link rel="shortcut icon" href="img/favicon32.ico">
    <link rel="shortcut icon" href="img/favicon48.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="head">
        <h1>スタッフ管理一覧</h1>
        <a href="admin_dashbord.php" class="cancel" style="background:#6c757d;">ダッシュボードへ戻る</a>
        <a class="insert_btn" href="user_register.php">新規登録</a>
    </div>
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>権限</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo ($user['role'] === 'admin') ? '管理者' : '一般'; ?></td>
                <td class="btn">
                    <a href="user_delete.php?id=<?php echo $user['id']; ?>" 
                       class="cancel" 
                       onclick="return confirm('本当にこのスタッフを削除しますか？\n※この操作は取り消せません。');">
                       削除
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>