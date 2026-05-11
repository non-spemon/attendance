<?php
// 一般ユーザー門番（打刻画面用）
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

// ② 「管理者（admin）でなければダメ」な門番（管理者画面用）
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

// セッションにある一時的なメールアドレスを返し、削除する
function getEmailValue() {
    $email = '';
    if (isset($_SESSION['temp_email'])) {
        $email = htmlspecialchars($_SESSION['temp_email']);
        unset($_SESSION['temp_email']); // 次回は空にする
    }
    return $email; // 値を外（value属性）に投げ返す
}

// 出勤と退勤の時間の前後関係が正しいかチェックする関数 update.php
function isValidTimeRange($date, $check_in, $check_out) {
    // もしどちらかが空なら比較できないので、一旦「正しい（true）」として返す
    if (empty($check_in) || empty($check_out)) {
        return true;
    }

    // 文字列の日時を数値（スタンプ）に変換
    $in_time  = strtotime($date . ' ' . $check_in);
    $out_time = strtotime($date . ' ' . $check_out);

    // 出勤が退勤より「前」であれば true（有効）、そうでなければ false（無効）
    return $in_time < $out_time;
}

// エラーメッセージ
function displayErrorMessage() {
    if (isset($_SESSION['error'])) {
        // HTMLに返す
        $error = htmlspecialchars($_SESSION['error']);
        echo "<p style='color: red; font-weight: bold;'>{$error}</p>";
        
        // 一度表示したら次は出ないように消す
        unset($_SESSION['error']);
    }
}

//　本番環境でのエラーが起きた時のメッセージ表示をローカルと切り替える
f// function.php の中
function handle_error($message) {
    // サーバーのログに詳細を記録（これは画面には出ない）
    error_log($message);

    // 環境変数を確認して表示を切り替え
    if (getenv('APP_ENV') === 'production') {
        exit('ただいまメンテナンス中です。');
    } else {
        exit('開発用エラー表示：' . $message);
    }
}

?>
