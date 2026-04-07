<?php session_start();  ?>

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
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red; font-weight: bold;">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']); // 一度表示したら、預かり所を空にする（リロードで消えるように）
                    ?>
                </p>
            <?php endif; ?>
            <div class="mail">
                <label>メールアドレス</label>
                <input type="text" name="email" value="<?php echo isset($_SESSION['temp_email']) ? htmlspecialchars($_SESSION['temp_email']) : ''; ?>">
            <?php 
            // 表示が終わったら、荷物を空にしておく（次回ログイン時に残らないように）
            unset($_SESSION['temp_email']); 
            ?>
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