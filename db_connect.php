<?php
// 環境変数から情報を取得
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    // 取得した変数を使って接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 公開用：エラーの詳細は出さず、ログに記録する
    error_log('DB接続エラー：' . $e->getMessage());
    exit('ただいまメンテナンス中です。');
}