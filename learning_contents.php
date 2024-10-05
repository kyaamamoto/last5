<?php
require_once 'admin_session_config.php';
require_once 'funcs.php';

// 管理者認証チェック
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    header("Location: login.php");
    exit();
}

$pdo = db_conn();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRFトークンの検証
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = '不正なリクエストです。';
    } else {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $youtube_video_id = filter_input(INPUT_POST, 'youtube_video_id', FILTER_SANITIZE_STRING);
        $learning_objective = filter_input(INPUT_POST, 'learning_objective', FILTER_SANITIZE_STRING);
        $difficulty = filter_input(INPUT_POST, 'difficulty', FILTER_SANITIZE_STRING);
        $estimated_time = filter_input(INPUT_POST, 'estimated_time', FILTER_SANITIZE_NUMBER_INT);
        $inquiry_theme = filter_input(INPUT_POST, 'inquiry_theme', FILTER_SANITIZE_STRING);
        $inquiry_process = filter_input(INPUT_POST, 'inquiry_process', FILTER_SANITIZE_STRING);
        $expected_approach = filter_input(INPUT_POST, 'expected_approach', FILTER_SANITIZE_STRING);
        $evaluation_criteria = filter_input(INPUT_POST, 'evaluation_criteria', FILTER_SANITIZE_STRING);
        $resources = filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING);
        $tasks = filter_input(INPUT_POST, 'tasks', FILTER_SANITIZE_STRING);

        // バリデーション
        if (
            empty($title) || empty($youtube_video_id) || empty($learning_objective) ||
            empty($difficulty) || empty($estimated_time) || empty($inquiry_theme) ||
            empty($inquiry_process) || empty($expected_approach) || empty($evaluation_criteria)
        ) {
            $error_message = '全ての必須項目を入力してください。';
        } else {
            // セッションにデータを追加保存
            $_SESSION['frontier_data']['title'] = $title;
            $_SESSION['frontier_data']['youtube_video_id'] = $youtube_video_id;
            $_SESSION['frontier_data']['learning_objective'] = $learning_objective;
            $_SESSION['frontier_data']['difficulty'] = $difficulty;
            $_SESSION['frontier_data']['estimated_time'] = $estimated_time;
            $_SESSION['frontier_data']['inquiry_theme'] = $inquiry_theme;
            $_SESSION['frontier_data']['inquiry_process'] = $inquiry_process;
            $_SESSION['frontier_data']['expected_approach'] = $expected_approach;
            $_SESSION['frontier_data']['evaluation_criteria'] = $evaluation_criteria;
            $_SESSION['frontier_data']['resources'] = $resources;
            $_SESSION['frontier_data']['tasks'] = $tasks;

            // 確認画面にリダイレクト
            header("Location: frontier_confirm.php");
            exit();
        }
    }
}

// 新しいCSRFトークンを取得（表示用）
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>学習コンテンツ情報 - ZOUUU Platform</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .navbar-custom {
            background-color: #0c344e;
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="cms.php">
        <img src="./img/ZOUUU.png" alt="ZOUUU Logo" class="d-inline-block align-top" height="30">
        ZOUUU Platform
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link">ようこそ <?php echo htmlspecialchars($_SESSION['name']); ?> さん</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cms.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">ログアウト</a>
            </li>
        </ul>
    </div>
</nav>

<!-- パンくずリスト -->
<nav aria-label="breadcrumb" class="mt-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="cms.php">ホーム</a></li>
    <li class="breadcrumb-item active" aria-current="page">学習コンテンツ情報</li>
  </ol>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">学習コンテンツ情報</h1>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo h($error_message); ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="title">タイトル：</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="youtube_video_id">YouTubeビデオID：</label>
            <input type="text" id="youtube_video_id" name="youtube_video_id" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="learning_objective">学習目標：</label>
            <textarea id="learning_objective" name="learning_objective" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="difficulty">難易度：</label>
            <select id="difficulty" name="difficulty" class="form-control" required>
                <option value="beginner">初級</option>
                <option value="intermediate">中級</option>
                <option value="advanced">上級</option>
            </select>
        </div>

        <div class="form-group">
            <label for="estimated_time">推定時間（分）：</label>
            <input type="number" id="estimated_time" name="estimated_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="inquiry_theme">探究テーマ：</label>
            <textarea id="inquiry_theme" name="inquiry_theme" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="inquiry_process">探究プロセス：</label>
            <textarea id="inquiry_process" name="inquiry_process" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="expected_approach">期待されるアプローチ：</label>
            <textarea id="expected_approach" name="expected_approach" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="evaluation_criteria">評価基準：</label>
            <textarea id="evaluation_criteria" name="evaluation_criteria" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="resources">リソース：</label>
            <textarea id="resources" name="resources" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="tasks">タスク：</label>
            <textarea id="tasks" name="tasks" class="form-control" rows="3"></textarea>
        </div>

        <!-- CSRFトークンを追加 -->
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
        
        <div class="form-group text-center">
        <a href="frontier_details.php" class="btn btn-secondary mr-2">戻る</a>
            <button type="submit" class="btn btn-primary">次へ</button>
        </div>
    </form>
</div>

<footer class="footer bg-light text-center py-3 mt-4">
    <div class="container">
        <span class="text-muted">Copyright &copy; 2024 <a href="#">ZOUUU</a>. All rights reserved.</span>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>