<?php session_start();  ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kintan</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="img/favicon16.ico">
    <link rel="shortcut icon" href="img/favicon32.ico">
    <link rel="shortcut icon" href="img/favicon48.ico">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
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