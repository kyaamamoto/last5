<?php
// セッションが開始されているか確認
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// デバッグ：セッション変数の内容を確認
echo "Before clearing session:<br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// セッション変数を全てクリア
$_SESSION = array();

// セッションのクッキーを削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// デバッグ：セッション変数をクリア後の確認
echo "After clearing session:<br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// セッションを破壊
session_destroy();

// デバッグ：セッションが破壊されたか確認
if (session_status() === PHP_SESSION_NONE) {
    echo "Session destroyed successfully.<br>";
} else {
    echo "Failed to destroy the session.<br>";
}

// 強制的にクッキーを削除（念のため）
setcookie("admin_session", "", time() - 3600, "/");

// 最後のデバッグ
echo "Cookies after delete:<br>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

// 最後にリダイレクト
header("Location: login.php");
exit();
?>