<?php session_start()
      require_once 'function.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require 'common_head.php'; ?>
    <title>Kintan</title>
</head>
<body>

    <div class="container">
        <h1>login</h1>
        <form action="auth.php" method="POST" novalidate>
            <?php displayErrorMessage(); ?>
                <div class="mail">
                    <label>メールアドレス</label>
                    <input type="text" name="email" value="<?php echo getEmailValue(); ?>">
                </div>
            <div class="pass">
                <label>パスワード</label>
                <input type="password" name="password">
            </div>
            <button class="login_btn" type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>