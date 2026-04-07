<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//  データベース接続
require_once 'db_connect.php';
require_once 'auth_admin.php';

// ユーザー一覧を取得
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
    <?php require 'common_head.php'; ?>
    <title>スタッフ一覧</title>
</head>
<body>
    <div class="head">
        <h1>スタッフ管理一覧</h1>
        <a href="admin_dashboard.php" class="cancel" style="background:#6c757d;">ダッシュボードへ戻る</a>
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
                    <?php if ((int)$user['id'] !== (int)$_SESSION['user_id']): ?>
                        <!-- 自分でない場合のみ、削除フォームを表示する -->
                        <form action="user_delete.php" method="POST" style="display:inline;" onsubmit="return confirm('本当に削除してもよろしいですか？\n※この操作は取り消せません。');">   
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete">削除</button>
                        </form>
                    <?php endif; ?>
                    
                    <!-- 自分の場合は、ここに何も書かない（空欄になる） -->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>