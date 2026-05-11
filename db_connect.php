<?php
// 1. 環境変数を取得（なければ右側のローカル用デフォルト値を使う）
$host   = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'kintan_db';
$user   = getenv('DB_USER') ?: 'root';
$pass   = getenv('DB_PASS') ?: ''; 
$charset = 'utf8mb4';

try {
    // 2. 接続文字列（DSN）を組み立てる
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log('DB接続エラー：' . $e->getMessage());
    exit('ただいまメンテナンス中です。');
}