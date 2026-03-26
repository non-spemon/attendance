<?php
session_start();
require_once 'db_connect.php';

// 権限チェック
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('権限がありません。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // 未入力チェック
    if ($name === '' || $email === '' || $password === '') {
        $_SESSION['error'] = '全項目入力してください。';
        header('Location: user_register.php');
        exit;
    }

    // パスワードのハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role
        ]);

        header('Location: admin_dashboard.php?message=user_added');
        exit;

    } catch (PDOException $e) {
        // メールアドレスの重複エラーなどの対策
        $_SESSION['error'] = '登録エラー：' . $e->getMessage();
        header('Location: user_register.php');
        exit;
    }
}