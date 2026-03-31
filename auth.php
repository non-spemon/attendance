<?php
session_start();
require_once 'db_connect.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// 入力欄が空じゃないかチェック
if ($email === '' || $password === ''){
    $_SESSION['error'] = 'メールアドレスまたはパスワードが正しくありません。';
    header('Location: index.php');
    exit;
}

// メールアドレスの形式をチェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = '有効なメールアドレスの形式で入力してください。';

    // 入力されたメアドを一時保存用に入れる
    $_SESSION['temp_email'] = $email;
    
    header('Location: index.php');
    exit;
}

try {
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();


    // 【重要】password_verifyを復活させ、パスワードが正しいかチェックする
    if ($user && password_verify($password, $user['password'])) { 
        
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];
        
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php'); 
        } else {
            header('Location: punch_screen.php');
        }
        exit;
    } else {
        // 認証失敗時
        $_SESSION['error'] = 'メールアドレスまたはパスワードが正しくありません。';
        header('Location: index.php');
        exit;
    }

} catch (PDOException $e) {
    exit('システムエラーが発生しました：' . $e->getMessage());
}