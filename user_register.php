<?php
session_start();
require_once 'function.php';
checkAdmin();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require 'common_head.php'; ?>
    <title>スタッフ登録</title>
</head>
<body>
    <div class="container">
        <h1>スタッフ登録</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form action="user_insert.php" method="POST">
            <div class="mail">
                <label>名前</label>
                <input type="text" name="name" required>
            </div>
            <div class="mail">
                <label>メールアドレス</label>
                <input type="email" name="email" required>
            </div>
            <div class="pass">
                <label>パスワード</label>
                <input type="password" name="password" required>
            </div>
            <div class="mail">
                <label>権限</label>
                <select name="role">
                    <option value="user">一般ユーザー</option>
                    <option value="admin">管理者</option>
                </select>
            </div>
            <br>
            <button class="regist_btn" type="submit">登録する</button>
            <a class="cancel" href="admin_dashboard.php">キャンセル</a>
        </form>
    </div>
</body>
</html>