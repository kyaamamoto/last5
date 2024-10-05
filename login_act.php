<?php
// セッション開始
session_start();

// 外部ファイル読み込み
require_once('funcs.php');

// POSTデータ取得
$lid = isset($_POST["lid"]) ? $_POST["lid"] : '';
$lpw = isset($_POST["lpw"]) ? $_POST["lpw"] : '';
$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

// CSRFトークンのチェック
if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
    $_SESSION['login_error'] = "不正なリクエストです。";
    header("Location: login.php");
    exit();
}

// データベース接続
$pdo = db_conn();

// データベースからユーザー情報取得
$sql = "SELECT * FROM user_table WHERE lid = :lid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->execute();
$val = $stmt->fetch(PDO::FETCH_ASSOC);

// 該当レコードがあればセッションに値を代入
if ($val && password_verify($lpw, $val["lpw"])) {
    // ログイン成功時
    $_SESSION["chk_ssid"] = session_id();
    $_SESSION["user_id"] = $val['id'];  // ユーザーIDをセッションに保存
    $_SESSION["name"] = $val['name'];
    $_SESSION["kanri_flg"] = $val['kanri_flg']; // 管理者フラグも保存
    header('Location: select.php');
} else {
    // ログイン失敗時
    $_SESSION['login_error'] = "ユーザー登録を行ってください。";
    header("Location: login.php");
}
exit();
?>