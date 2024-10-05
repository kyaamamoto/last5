<?php
// セッション設定を適用
require_once 'session_config.php';

// セキュリティヘッダーを適用
require_once 'security_headers.php';

// その他の必要なファイルのインクルード
require_once 'funcs.php';

// CSRFトークンの生成
$csrf_token = generateToken();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="icon" type="image/png" href="./img/favicon.ico">
    <link rel="stylesheet" href="./css/style4.css">
    <link rel="stylesheet" href="./css/education.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        .message { color: green; font-weight: bold; margin-top: 10px; }
        .error { color: red; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
<header>
        <div class="container">
            <nav>
            <div class="logo">
                  <a href="education.html">ZOUUU</a>
                </div>
                <ul>
                    <li><a href="education.html#about">初めての方へ</a></li>
                    <li><a href="education.html#contact">お問い合わせ</a></li>
                    <li><a href="login_holder.php">ログイン</a></li>
                    <li><a href="mypage_entry.php" class="btn-register">会員登録</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login-container">
        <h1>ログイン</h1>
        <?php
        // 登録成功メッセージの表示
        if (isset($_SESSION['registration_success'])) {
            echo "<p class='message' role='status'>ユーザー登録が完了しました。ログインしてください。</p>";
            unset($_SESSION['registration_success']);
        }
        ?>
        <form name="form1" action="auth_holder.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="email">メールアドレス:</label>
                <input type="email" name="email" id="email" required aria-required="true">
            </div>
            <div class="form-group">
                <label for="lpw">パスワード:</label>
                <input type="password" name="lpw" id="lpw" required aria-required="true">
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <button type="submit" class="btn">ログイン</button>
        </form>
        <div class="btn-register mt-3">
            <a href="mypage_entry.php">ユーザー登録はこちら</a>
        </div>
        <?php
        // ログインエラーメッセージの表示
        if (isset($_SESSION['login_error'])) {
            echo "<p class='error' role='alert'>" . h($_SESSION['login_error']) . "</p>";
            unset($_SESSION['login_error']);
        }
        ?>
    </div>

    <script>
    function validateForm() {
        var email = document.forms["form1"]["email"].value;
        var lpw = document.forms["form1"]["lpw"].value;
        if (email == "" || lpw == "") {
            alert("メールアドレスとパスワードを入力してください");
            return false;
        }
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("有効なメールアドレスを入力してください");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>