<?php

require_once "auth/auth.php";
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
      <meta charset="UTF-8">
      <meta name="viewpoint" content="width=device-width, initial-scle=1.0">
      <title>ホーム - TODOアプリ</title>
      <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
      <?php include_once "includes/header.php"; ?>
      <?php include_once "includes/navbar.php"; ?>

      <div class="container">
          <div class="jumbotron">
              <h2>TODOアプリへようこそ</h2>
              <p>このアプリでは簡単にTODOタスクを管理することができます</p>

              <?php if (isLoggedIn()): ?>
                  <p>
                      <a href="/todo/read.php" class="btn btn-primary">TODOリストを見る</a>
                      <a href="todo/create.php" class="btn btn-success">新しいTODOを作成</a>
                  </p>
              <?php else: ?>
                  <p>
                      <a href="/auth/login.php" class="btn btn-primary">ログイン</a>
                      <a href="/auth/register.php" class="btn btn-success">アカウント登録</a>
                  </p>
              <?php endif; ?>
          </div>

          <div class="features">
              <h3>主な機能</h3>
              <div class="feature-grid">
                  <div class="feature-item">
                      <h4>タスク管理</h4>
                      <p>簡単にタスクを作成、編集、削除できます</p>
                  </div>
                  <div class="feature-item">
                      <h4>ステータスの追跡</h4>
                      <p>タスクの進捗状況を追跡できます</p>
                  </div>
                  <div class="feature-item">
                      <h4>セキュアな認証</h4>
                      <p>ユーザー情報は安全に保護されます</p>
                  </div>
              </div>
          </div>
      </div>
  
      <?php include_once "includes/footer.php"; ?>
  </body>
</html>



