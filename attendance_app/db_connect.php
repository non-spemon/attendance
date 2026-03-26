<?php
// 文字列を関数に代入
$host = 'localhost';
$dbname = 'kintan_db'; 
$user = 'root'; 
$pass = ''; 

try {
    // データベースに接続（PDOという安全な方式を使用）
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // PDOにルールを設定する↓
    // エラー時に報告を投げる設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // データを連想配列形式で取得する設定
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 失敗した場合はエラーを表示して停止
    exit('DB接続エラー：' . $e->getMessage());
}